<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->enum('content_type', ['feed', 'story', 'reels']);
            $table->integer('content_index');
            $table->date('scheduled_date')->nullable();
            $table->enum('status', ['Belum', 'Selesai'])->default('Belum');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_progress');
    }
};
