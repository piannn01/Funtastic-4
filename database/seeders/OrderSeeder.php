<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Service;
use App\Models\OrderProgress;
use App\Models\OrderContent;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $serviceBasic = Service::where('name', 'Basic Starter')->first();
        $servicePro   = Service::where('name', 'Pro Growth')->first();
        $servicePrem  = Service::where('name', 'Premium Max')->first();

        // Kalau services belum ada, stop biar gak error FK
        if (!$serviceBasic || !$servicePro || !$servicePrem) {
            return;
        }

        $orders = [
            [
                'invoice_code' => strtoupper(Str::random(10)),
                'redirect_url' => 'https://funtastic4.web.id/invoice/demo-basic',
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'phone' => '081234567890',
                'instagram' => 'https://instagram.com/budisantoso',
                'instagram_username' => 'budisantoso',
                'service_id' => $serviceBasic->id,
                'notes' => 'Fokus konten edukasi produk dan promo mingguan.',
                'price' => $serviceBasic->price,
                'payment_status' => 'paid',
                'status' => 'processing',
                'midtrans_token' => null,
                'midtrans_order_id' => 'ORDER-'.Str::random(6),
                'progress' => 25,
                'progress_note' => 'Progress otomatis: 25%',
                'kode_unik' => 'BASIC'.Str::upper(Str::random(4)),
                'progress_percent' => 25,
            ],
            [
                'invoice_code' => strtoupper(Str::random(10)),
                'redirect_url' => 'https://funtastic4.web.id/invoice/demo-pro',
                'name' => 'Siti Aisyah',
                'email' => 'siti@gmail.com',
                'phone' => '082233445566',
                'instagram' => 'https://instagram.com/sitiaisyah',
                'instagram_username' => 'sitiaisyah',
                'service_id' => $servicePro->id,
                'notes' => 'Konten fokus engagement + story daily.',
                'price' => $servicePro->price,
                'payment_status' => 'paid',
                'status' => 'completed',
                'midtrans_token' => null,
                'midtrans_order_id' => 'ORDER-'.Str::random(6),
                'progress' => 100,
                'progress_note' => 'Progress otomatis: 100%',
                'kode_unik' => 'PRO'.Str::upper(Str::random(4)),
                'progress_percent' => 100,
            ],
            [
                'invoice_code' => strtoupper(Str::random(10)),
                'redirect_url' => 'https://funtastic4.web.id/invoice/demo-premium',
                'name' => 'Andi Wijaya',
                'email' => 'andi@gmail.com',
                'phone' => '081998877665',
                'instagram' => 'https://instagram.com/andiwijaya',
                'instagram_username' => 'andiwijaya',
                'service_id' => $servicePrem->id,
                'notes' => 'Konten premium dengan reels mingguan.',
                'price' => $servicePrem->price,
                'payment_status' => 'pending',
                'status' => 'pending',
                'midtrans_token' => null,
                'midtrans_order_id' => 'ORDER-'.Str::random(6),
                'progress' => 0,
                'progress_note' => null,
                'kode_unik' => 'PREM'.Str::upper(Str::random(4)),
                'progress_percent' => 0,
            ],
        ];

        foreach ($orders as $orderData) {
            $order = Order::create($orderData);

            // ===== BUAT PROGRESS SESUAI SERVICE =====
            $service = Service::find($order->service_id);

            // progress feeds
            for ($i=1; $i <= $service->feed; $i++) {
                OrderProgress::create([
                    'order_id' => $order->id,
                    'content_type' => 'feed',
                    'content_index' => $i,
                    'scheduled_date' => now()->addDays($i),
                    'status' => ($order->progress_percent >= 25 && $i <= 2) ? 'Selesai' : 'Belum',
                ]);
            }

            // progress stories
            for ($i=1; $i <= $service->stories; $i++) {
                OrderProgress::create([
                    'order_id' => $order->id,
                    'content_type' => 'story',
                    'content_index' => $i,
                    'scheduled_date' => now()->addDays($i),
                    'status' => ($order->progress_percent >= 25 && $i == 1) ? 'Selesai' : 'Belum',
                ]);
            }

            // progress reels
            for ($i=1; $i <= $service->video_reels; $i++) {
                OrderProgress::create([
                    'order_id' => $order->id,
                    'content_type' => 'reels',
                    'content_index' => $i,
                    'scheduled_date' => now()->addDays($i * 3),
                    'status' => ($order->progress_percent == 100) ? 'Selesai' : 'Belum',
                ]);
            }

            // ===== SAMPLE KONTEN UNTUK TESTING VIEW/UPLOAD =====
            OrderContent::create([
                'order_id' => $order->id,
                'content_type' => 'feed',
                'file_path' => 'order_contents/sample_feed_'.$order->id.'.png',
                'caption' => 'Caption feed sample untuk order '.$order->kode_unik,
            ]);

            if ($service->stories > 0) {
                OrderContent::create([
                    'order_id' => $order->id,
                    'content_type' => 'story',
                    'file_path' => 'order_contents/sample_story_'.$order->id.'.png',
                    'caption' => null,
                ]);
            }

            if ($service->video_reels > 0) {
                OrderContent::create([
                    'order_id' => $order->id,
                    'content_type' => 'reels',
                    'file_path' => 'order_contents/sample_reels_'.$order->id.'.mp4',
                    'caption' => null,
                ]);
            }
        }
    }
}
