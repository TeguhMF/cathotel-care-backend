<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('snap_token')->nullable(); // Dari Midtrans
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('payment_type')->nullable(); // bank_transfer, gopay, qris, dll
            $table->enum('payment_status', ['pending', 'settlement', 'expire', 'cancel'])->default('pending');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
