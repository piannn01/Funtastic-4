<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Basic Starter',
                'feed' => 8,
                'stories' => 8,
                'video_reels' => 0,
                'duration' => 30,
                'description' => 'Paket pemula untuk social media management. Cocok untuk UMKM atau personal brand baru.',
                'price' => 800000,
                'status' => 'active',
            ],
            [
                'name' => 'Pro Growth',
                'feed' => 12,
                'stories' => 12,
                'video_reels' => 2,
                'duration' => 30,
                'description' => 'Paket berkembang untuk meningkatkan engagement dan konsistensi konten.',
                'price' => 1500000,
                'status' => 'active',
            ],
            [
                'name' => 'Premium Max',
                'feed' => 20,
                'stories' => 25,
                'video_reels' => 4,
                'duration' => 30,
                'description' => 'Paket premium lengkap untuk kebutuhan konten intensif dan brand besar.',
                'price' => 2800000,
                'status' => 'active',
            ],
        ];

        foreach ($services as $data) {
            Service::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
