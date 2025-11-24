<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'name' => 'Desain Grafis',
                'description' => 'Layanan pembuatan desain profesional seperti logo, banner, brosur, dan kebutuhan visual lainnya.',
                'price' => 250000,
                'duration' => '3 Hari',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Mobile Programming',
                'description' => 'Pengembangan aplikasi mobile berbasis Android dan iOS sesuai kebutuhan bisnis Anda.',
                'price' => 1500000,
                'duration' => '14 Hari',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Reparasi Gadget',
                'description' => 'Layanan perbaikan gadget seperti smartphone dan tablet dengan teknisi berpengalaman.',
                'price' => 100000,
                'duration' => '2 Hari',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Web Development',
                'description' => 'Pembuatan dan pengembangan website profesional untuk bisnis, toko online, maupun portofolio pribadi.',
                'price' => 2000000,
                'duration' => '10 Hari',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
