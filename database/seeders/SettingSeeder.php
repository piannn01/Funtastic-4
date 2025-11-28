<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Funtastic 4 - Jasa Pengelola Media Sosial',
                'logo' => 'logo.png',
                'description' => 'Layanan social media management untuk UMKM, brand, dan personal branding.',
                'email' => 'support@funtastic4.web.id',
                'whatsapp' => '6281234567890',
                'address' => 'Jl. KH. Hasyim Asy’ari No. 17, Jombang, Jawa Timur',
            ]
        );
    }
}
