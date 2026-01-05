<?php

namespace App\Http\Controllers\user;

use Midtrans\Config;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use function Symfony\Component\Clock\now;

class PaymentController extends Controller
{
    public function midtransCallback(Request $request)
    {
        // 1. Konfigurasi Midtrans
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            Log::error("Midtrans Notification Error: " . $e->getMessage());
            return response()->json(['error' => 'Invalid notification signature.'], 400);
        }

        $transactionStatus = $notif->transaction_status;
        $fraudStatus       = $notif->fraud_status;
        $orderId           = $notif->order_id; // Ini adalah invoice_number

        // 2. Cari order berdasarkan invoice_number
        $order = Order::where('invoice_number', $orderId)->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found.'], 404);
        }

        // 3. Mapping Status ke Enum Database Anda:
        // Enum: ['Pending','Berhasil','Gagal','Expired','Refound']

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $this->updateOrderAndPayment($order, 'Berhasil', 'processing', $notif);
            }
        } else if ($transactionStatus == 'settlement') {
            $this->updateOrderAndPayment($order, 'Berhasil', 'processing', $notif);
        } else if ($transactionStatus == 'pending') {
            $this->updateOrderAndPayment($order, 'Pending', 'new', $notif);
        } else if ($transactionStatus == 'expire') {
            $this->updateOrderAndPayment($order, 'Expired', 'cancelled', $notif);
        } else if (in_array($transactionStatus, ['cancel', 'deny'])) {
            $this->updateOrderAndPayment($order, 'Gagal', 'cancelled', $notif);
        } else if ($transactionStatus == 'refund') {
            $this->updateOrderAndPayment($order, 'Refound', 'cancelled', $notif);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Update status Order dan Payment sesuai Schema Project DuaRasa
     */
    protected function updateOrderAndPayment(Order $order, string $payStatus, string $ordStatus, Notification $notif)
    {
        DB::transaction(function () use ($order, $payStatus, $ordStatus, $notif) {

            // A. Update Table 'orders'
            $order->update([
                'payment_status' => $payStatus, // ['Pending','Berhasil',...]
                'order_status'   => $ordStatus  // ['new','processing',...]
            ]);

            // B. Update/Create Table 'payments'
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'method'           => $notif->payment_type,
                    'transaction_code' => $notif->transaction_id, // ID transaksi dari Midtrans
                    'amount'           => $notif->gross_amount,
                    'payment_status'   => $payStatus,
                    'payment_time'     => ($payStatus == 'Berhasil') ? now() : null,
                ]
            );
        });
    }
}
