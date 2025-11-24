<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Order;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total data
        $totalServices = Service::count();
        $totalOrders = Order::count();
        $totalTestimonials = Testimonial::count();

        return view('admin.dashboard', compact(
            'totalServices',
            'totalOrders',
            'totalTestimonials'
        ));
    }
}
