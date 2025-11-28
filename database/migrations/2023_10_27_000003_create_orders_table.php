<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // bigint unsigned
            $table->string('invoice_code', 50)->nullable();
            $table->string('redirect_url')->nullable();

            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();

            $table->string('instagram')->nullable();
            $table->string('instagram_username')->nullable();

            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();

            $table->text('notes')->nullable();
            $table->decimal('price', 15, 2)->nullable();

            $table->enum('payment_status', ['pending','paid','failed'])
                ->default('pending');

            $table->enum('status', ['pending','processing','completed','cancelled'])
                ->default('pending');

            $table->string('midtrans_token')->nullable();
            $table->string('midtrans_order_id')->nullable();

            $table->integer('progress')->default(0);
            $table->text('progress_note')->nullable();

            $table->string('kode_unik')->nullable()->unique();
            $table->integer('progress_percent')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
