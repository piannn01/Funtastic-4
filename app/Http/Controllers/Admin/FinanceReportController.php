<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Service;
use Carbon\Carbon;

class FinanceReportController extends Controller
{
    public function index(Request $request)
    {
        // =========================
        // FILTER INPUT
        // =========================
        $from = $request->query('from')
            ? Carbon::parse($request->query('from'))->startOfDay()
            : now()->startOfMonth();

        $to = $request->query('to')
            ? Carbon::parse($request->query('to'))->endOfDay()
            : now()->endOfMonth();

        $serviceId = $request->query('service_id');
        $paymentStatus = $request->query('payment_status'); // boleh kosong utk semua

        // =========================
        // BASE QUERY
        // =========================
        $ordersQuery = Order::with('service')
            ->whereBetween('created_at', [$from, $to]);

        if ($serviceId) {
            $ordersQuery->where('service_id', $serviceId);
        }

        if ($paymentStatus) {
            $ordersQuery->where('payment_status', $paymentStatus);
        }

        // =========================
        // DETAIL ORDERS (TABLE)
        // =========================
        $orders = (clone $ordersQuery)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        // =========================
        // SUMMARY
        // =========================
        $paidOrdersQuery = (clone $ordersQuery)->where('payment_status', 'paid');

        $totalRevenue     = (clone $paidOrdersQuery)->sum('price');
        $totalPaidOrders  = (clone $paidOrdersQuery)->count();
        $totalAllOrders   = (clone $ordersQuery)->count();

        $avgOrderValue = $totalPaidOrders > 0
            ? $totalRevenue / $totalPaidOrders
            : 0;

        // revenue per service
        $revenueByService = (clone $paidOrdersQuery)
            ->selectRaw('service_id, SUM(price) as total')
            ->groupBy('service_id')
            ->with('service')
            ->orderByDesc('total')
            ->get();

        // =========================
        // CHART MONTHLY REVENUE
        // =========================
        $monthlyRevenue = (clone $paidOrdersQuery)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(price) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $services = Service::orderBy('name')->get();

        return view('admin.reports.finance', compact(
            'orders',
            'services',
            'from',
            'to',
            'serviceId',
            'paymentStatus',
            'totalRevenue',
            'totalPaidOrders',
            'totalAllOrders',
            'avgOrderValue',
            'revenueByService',
            'monthlyRevenue'
        ));
    }
}
