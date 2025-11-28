<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function create($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('testimonial.create', compact('order'));
        // kalau view kamu di landing/testimonial/create.blade.php
        // return view('landing.testimonial.create', compact('order'));
    }

    public function store(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'message' => 'required|string|min:5|max:1000',
        ]);

        Testimonial::create([
            'order_id' => $order->id,
            'name'     => $order->name,
            'email'    => $order->email,
            'rating'   => $validated['rating'],
            'message'  => $validated['message'],
            'status'   => 0, // pending
        ]);

        return redirect()->back()
            ->with('success', 'Testimoni berhasil dikirim. Terima kasih ya!');
    }
}
