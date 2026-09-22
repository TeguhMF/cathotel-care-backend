<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required',
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'cat_name' => 'required|string',
            'cat_breed' => 'nullable|string',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
        ]);

        $room = Room::find($request->room_id);
        $pricePerNight = $room ? $room->price_per_night : 50000;
        $roomName = $room ? $room->name : 'Cat Room Standard';
        $roomId = $room ? $room->id : 1;

        $checkIn = new \DateTime($request->check_in);
        $checkOut = new \DateTime($request->check_out);
        $nights = $checkIn->diff($checkOut)->days;
        if ($nights < 1) $nights = 1;

        $totalPrice = $nights * $pricePerNight;
        $dpAmount = $totalPrice * 0.30;

        $bookingCode = 'CHC-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'room_id' => $roomId,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'cat_name' => $request->cat_name,
            'cat_breed' => $request->cat_breed ?? 'Domestic',
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'total_nights' => $nights,
            'total_price' => $totalPrice,
            'dp_amount' => $dpAmount,
            'status' => 'pending_dp',
        ]);

        Config::$serverKey = trim(env('MIDTRANS_SERVER_KEY'));
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $booking->booking_code,
                'gross_amount' => (int) $dpAmount,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'phone' => $request->customer_phone,
            ],
            'item_details' => [
                [
                    'id' => 'DP-' . $booking->room_id,
                    'price' => (int) $dpAmount,
                    'quantity' => 1,
                    'name' => 'DP 30% Booking ' . $roomName,
                ]
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Midtrans Error: ' . $e->getMessage()
            ], 500);
        }

        Payment::create([
            'booking_id' => $booking->id,
            'snap_token' => $snapToken,
            'amount' => $dpAmount,
            'payment_status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reservasi berhasil dibuat',
            'data' => [
                'booking' => $booking,
                'snap_token' => $snapToken,
            ]
        ], 201);
    }
}