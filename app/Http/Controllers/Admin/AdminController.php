<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Order;
use App\Models\Portfolio;
use App\Models\Testimonial;
use App\Models\Message;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Hitung jumlah data dari tiap tabel
        $serviceCount = Service::count();
        $orderCount = Order::count();
        $portfolioCount = Portfolio::count();
        $testimonialCount = Testimonial::count();
        $messageCount = Message::count(); // ✅ tambahkan ini

        // Kirim semua data ke view dashboard admin
        return view('admin.dashboard', compact(
            'serviceCount',
            'orderCount',
            'portfolioCount',
            'testimonialCount',
            'messageCount' // ✅ tambahkan ini juga
        ));
    }
}
