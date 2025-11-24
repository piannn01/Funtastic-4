<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function handleNotification(Request $request)
    {
        // === Setup Midtrans ===
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $notif = new Notification();

        $orderId = $notif->order_id;
        $transaction = $notif->transaction_status;
        $fraud = $notif->fraud_status ?? null;

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // === Update status order berdasarkan notifikasi ===
        if ($transaction == 'capture') {
            if ($fraud == 'challenge') {
                $order->status = 'pending';
            } else {
                $order->status = 'paid';
            }
        } elseif ($transaction == 'settlement') {
            $order->status = 'paid';
        } elseif ($transaction == 'pending') {
            $order->status = 'pending';
        } elseif ($transaction == 'deny') {
            $order->status = 'failed';
        } elseif ($transaction == 'expire') {
            $order->status = 'expired';
        } elseif ($transaction == 'cancel') {
            $order->status = 'cancelled';
        }

        $order->save();

        return response()->json(['message' => 'Order updated successfully']);
    }
}
