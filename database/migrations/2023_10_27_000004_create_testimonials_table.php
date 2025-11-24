<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');     // Nama klien
            $table->string('company')->nullable(); // Nama perusahaan / proyek (boleh kosong)
            $table->text('message');           // Isi testimoni
            $table->unsignedTinyInteger('rating')->default(5); // Rating 1–5
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status tampil atau tidak
            $table->string('photo')->nullable(); // Foto profil klien
            $table->timestamps();               // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
