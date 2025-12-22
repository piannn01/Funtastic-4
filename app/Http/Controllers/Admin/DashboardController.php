<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $totalServices = Service::count();
        $totalOrders = Order::count();
        $totalTestimonials = Testimonial::count();

        // Statistik pembayaran
        $paidOrders = Order::where('payment_status', 'paid')->count();
        $pendingPaymentOrders = Order::where('payment_status', 'pending')->count();
        $failedPaymentOrders = Order::where('payment_status', 'failed')->count();

        // Tanggungan kerja: sudah dibayar tapi belum selesai
        $unfinishedPaidOrdersCount = Order::where('payment_status', 'paid')
            ->where('status', '!=', 'completed')
            ->count();

        // Daftar tanggungan (urut progress kecil dulu biar prioritas)
        $unfinishedPaidOrders = Order::with('service')
            ->where('payment_status', 'paid')
            ->where('status', '!=', 'completed')
            ->orderByRaw('COALESCE(progress_percent, 0) ASC')
            ->latest()
            ->take(8)
            ->get();

        // Ringkasan penyelesaian paid orders (%)
        $paidTotal = max($paidOrders, 1);
        $completedPaid = Order::where('payment_status', 'paid')
            ->where('status', 'completed')
            ->count();
        $completionRate = (int) round(($completedPaid / $paidTotal) * 100);

        // Pesanan terbaru (biar admin cepat pantau)
        $recentOrders = Order::with('service')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalServices',
            'totalOrders',
            'totalTestimonials',
            'paidOrders',
            'pendingPaymentOrders',
            'failedPaymentOrders',
            'unfinishedPaidOrdersCount',
            'unfinishedPaidOrders',
            'completionRate',
            'recentOrders'
        ));
    }
}
