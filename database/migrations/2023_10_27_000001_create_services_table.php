<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto increment
            $table->string('name');
            $table->integer('feed')->default(0);
            $table->integer('stories')->default(0);
            $table->integer('video_reels')->default(0);
            $table->text('duration')->default('30 Hari');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0.00);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
