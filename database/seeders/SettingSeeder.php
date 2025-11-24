<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada, untuk memastikan hanya ada 1 baris
        Setting::truncate();
        
        Setting::create([
            'site_name' => 'Funtastic 4 - Jasa Pengelola Media Sosial',
            'logo' => 'logo.png',
            'description' => 'Kami membantu brand Anda tumbuh melalui strategi pengelolaan media sosial yang efektif dan kreatif.',
            'email' => 'contact@funtastic4.com',
            'whatsapp' => '6289505721124',
            'address' => 'Jl. KH. Hasyim Asy’ari No. 17, Jombang, Jawa Timur',
        ]);
    }
}