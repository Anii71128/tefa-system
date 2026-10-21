<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Relasi ke pesanan
            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            // Informasi transaksi dari payment gateway
            $table->string('gateway')->default('midtrans');
            $table->string('gateway_order_id')->unique();
            $table->string('gateway_transaction_id')->nullable();

            // Informasi pembayaran
            $table->decimal('amount', 15, 2);
            $table->string('payment_method')->nullable();

            $table->string('status')->default('pending');
            $table->string('transaction_token')->nullable();
            $table->text('payment_url')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
