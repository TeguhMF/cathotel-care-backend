<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // Contoh: CHC-20260925-X8A2
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            
            // Detail Kucing & Reservasi
            $table->string('cat_name');
            $table->string('cat_breed')->nullable(); // Ras kucing (Persia, Anggora, dll)
            $table->text('special_notes')->nullable(); // Alergi, obat, dll
            
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('total_nights');
            $table->integer('total_price');
            
            // Status Transaksi & Pembayaran
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid', 'failed', 'refunded'])->default('unpaid');
            $table->string('snap_token')->nullable(); // Untuk Midtrans di Poin 2 nanti
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};