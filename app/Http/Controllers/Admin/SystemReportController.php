<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\Testimonial;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemReportController extends Controller
{
    private function getDateRange(Request $request): array
    {
        // Default: 30 hari terakhir
        $start = $request->get('start_date')
            ? Carbon::parse($request->get('start_date'))->startOfDay()
            : now()->subDays(30)->startOfDay();

        $end = $request->get('end_date')
            ? Carbon::parse($request->get('end_date'))->endOfDay()
            : now()->endOfDay();

        return [$start, $end];
    }

    private function buildReportData(Request $request): array
    {
        [$start, $end] = $this->getDateRange($request);

        // Orders in range
        $ordersQuery = Order::whereBetween('created_at', [$start, $end]);

        $totalOrders = (clone $ordersQuery)->count();

        $paidOrders = (clone $ordersQuery)->where('payment_status', 'paid')->count();
        $pendingOrders = (clone $ordersQuery)->where('payment_status', 'pending')->count();
        $failedOrders = (clone $ordersQuery)->where('payment_status', 'failed')->count();

        $completedOrders = (clone $ordersQuery)->where('status', 'completed')->count();
        $unfinishedOrders = (clone $ordersQuery)->where('status', '!=', 'completed')->count();

        $totalRevenue = (clone $ordersQuery)
            ->where('payment_status', 'paid')
            ->sum('price');

        // Tanggungan: paid tapi belum completed (ambil detail)
        $unfinishedPaidOrders = Order::with('service')
            ->whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->where('status', '!=', 'completed')
            ->orderByRaw('COALESCE(progress, 0) ASC') // kamu pakai field "progress"
            ->latest()
            ->take(20)
            ->get();

        // Transaksi paid terbaru
        $recentPaidOrders = Order::with('service')
            ->whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->latest()
            ->take(20)
            ->get();

        // Top layanan terlaris (paid saja)
        $topServices = Order::select('service_id', DB::raw('COUNT(*) as total_orders'), DB::raw('SUM(price) as total_income'))
            ->whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->groupBy('service_id')
            ->orderByDesc('total_orders')
            ->with('service')
            ->take(10)
            ->get();

        // Statistik layanan & testimoni
        $totalServices = Service::count();

        $testimonialQuery = Testimonial::whereBetween('created_at', [$start, $end]);
        $totalTestimonials = (clone $testimonialQuery)->count();
        $avgRating = (clone $testimonialQuery)->avg('rating');
        $avgRating = $avgRating ? round($avgRating, 2) : 0;

        return [
            'start' => $start,
            'end' => $end,

            'totalOrders' => $totalOrders,
            'paidOrders' => $paidOrders,
            'pendingOrders' => $pendingOrders,
            'failedOrders' => $failedOrders,

            'completedOrders' => $completedOrders,
            'unfinishedOrders' => $unfinishedOrders,

            'totalRevenue' => $totalRevenue,

            'totalServices' => $totalServices,
            'totalTestimonials' => $totalTestimonials,
            'avgRating' => $avgRating,

            'topServices' => $topServices,
            'unfinishedPaidOrders' => $unfinishedPaidOrders,
            'recentPaidOrders' => $recentPaidOrders,
        ];
    }

    public function index(Request $request)
    {
        $data = $this->buildReportData($request);
        return view('admin.reports.summary.index', $data);
    }

    public function download(Request $request)
    {
        $data = $this->buildReportData($request);

        $pdf = Pdf::loadView('pdf.reports.summary', $data)
            ->setPaper('A4', 'portrait');

        $filename = 'laporan-keseluruhan-' . now()->format('Ymd-His') . '.pdf';
        return $pdf->download($filename);
    }
}
