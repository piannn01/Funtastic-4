<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;
use App\Models\Order;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil order yang sudah paid/completed buat testimoni
        $orders = Order::where('payment_status', 'paid')->take(2)->get();

        foreach ($orders as $order) {
            Testimonial::create([
                'order_id' => $order->id,
                'name' => $order->name,
                'email' => $order->email,
                'rating' => 5,
                'image' => null,
                'message' => 'Pelayanan bagus, kontennya konsisten dan sesuai brief. Sangat recommended!',
                'status' => true,
            ]);
        }
    }
}
