<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'), // Ganti 'admin123' dengan password yang aman
            'role' => 'superadmin',
        ]);
    }
}