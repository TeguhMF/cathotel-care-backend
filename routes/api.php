<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\AuthController;

// Katalog Kamar
Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/rooms/{id}', [RoomController::class, 'show']);

// Route ini dipanggil oleh React saat form di-submit
Route::post('/bookings', [BookingController::class, 'store']);

// Route ini HANYA dipanggil oleh server Midtrans (Webhook) di belakang layar
Route::post('/midtrans/callback', [BookingController::class, 'notificationHandler']);

// Autentikasi
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/bookings/user/{id}', [BookingController::class, 'getUserBookings']);