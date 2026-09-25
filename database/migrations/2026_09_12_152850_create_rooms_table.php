<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');             // Contoh: Deluxe Cat Suite
            $table->string('category');         // Contoh: Standard, Deluxe, VIP
            $table->integer('price_per_night'); // Contoh: 150000
            $table->integer('capacity');        // Kapasitas max kucing, contoh: 2
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};