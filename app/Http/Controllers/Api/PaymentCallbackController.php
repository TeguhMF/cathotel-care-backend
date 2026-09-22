<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        try {
            $notif = new Notification();

            $transactionStatus = $notif->transaction_status;
            $orderId = $notif->order_id;
            $paymentType = $notif->payment_type;
            $transactionId = $notif->transaction_id;

            $booking = Booking::where('booking_code', $orderId)->first();
            if (!$booking) {
                return response()->json(['message' => 'Booking not found'], 444);
            }

            $payment = Payment::where('booking_id', $booking->id)->first();

            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                $payment->update([
                    'payment_status' => 'settlement',
                    'payment_type' => $paymentType,
                    'transaction_id' => $transactionId,
                ]);
                $booking->update(['status' => 'confirmed']);
            } elseif ($transactionStatus == 'pending') {
                $payment->update(['payment_status' => 'pending']);
            } else {
                $payment->update(['payment_status' => 'cancel']);
                $booking->update(['status' => 'cancelled']);
            }

            return response()->json(['message' => 'Callback handled successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}