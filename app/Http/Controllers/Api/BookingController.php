<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class BookingController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'cat_name' => 'required|string|max:255',
            'cat_breed' => 'nullable|string|max:255',
            'special_notes' => 'nullable|string',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $room = Room::findOrFail($request->room_id);

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $totalNights = $checkIn->diffInDays($checkOut);
        if ($totalNights < 1) $totalNights = 1;

        $totalPrice = $totalNights * $room->price_per_night;
        $dpAmount = (int) round($totalPrice * 0.3); // Nominal DP 30%

        $bookingCode = 'CHC-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => $request->user_id,
            'room_id' => $request->room_id,
            'cat_name' => $request->cat_name,
            'cat_breed' => $request->cat_breed,
            'special_notes' => $request->special_notes,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'total_nights' => $totalNights,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $booking->load(['user', 'room']);

        // Parameter Transaksi Midtrans (Hanya tagih nominal DP 30%)
        $params = [
            'transaction_details' => [
                'order_id' => $booking->booking_code,
                'gross_amount' => $dpAmount,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
                'phone' => $booking->user->phone ?? '081234567890',
            ],
            'item_details' => [
                [
                    'id' => $room->id,
                    'price' => $dpAmount,
                    'quantity' => 1,
                    'name' => 'DP (30%) ' . $room->name,
                ]
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $booking->snap_token = $snapToken;
            $booking->save();
        } catch (\Exception $e) {
            // Fallback jika Midtrans belum di-configure dengan key asli
            $booking->snap_token = 'DEV-MOCK-' . Str::random(10);
            $booking->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Pemesanan kamar berhasil dibuat',
            'data' => $booking
        ], 201);
    }

    // Webhook Notification dari Midtrans
    public function notificationHandler(Request $request)
    {
        try {
            $notif = new Notification();

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;

            $booking = Booking::where('booking_code', $orderId)->first();
            if (!$booking) {
                return response()->json(['message' => 'Booking not found'], 404);
            }

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $booking->payment_status = 'unpaid';
                    } else {
                        $booking->payment_status = 'paid';
                        $booking->status = 'confirmed';
                    }
                }
            } else if ($transaction == 'settlement') {
                $booking->payment_status = 'paid';
                $booking->status = 'confirmed';
            } else if ($transaction == 'pending') {
                $booking->payment_status = 'unpaid';
            } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
                $booking->payment_status = 'failed';
                $booking->status = 'cancelled';
            }

            $booking->save();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}