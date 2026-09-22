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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // contoh: CHC-20260912-001
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('cat_name');
            $table->string('cat_breed');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('total_nights');
            $table->decimal('total_price', 12, 2);
            $table->decimal('dp_amount', 12, 2); // Nominal DP 30%
            $table->enum('status', ['pending_dp', 'confirmed', 'completed', 'cancelled'])->default('pending_dp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
