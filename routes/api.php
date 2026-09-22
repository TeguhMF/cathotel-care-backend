<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentCallbackController;
use App\Http\Controllers\Api\AuthController;

Route::get('/rooms', [RoomController::class, 'index']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::post('/midtrans-callback', [PaymentCallbackController::class, 'handle']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
