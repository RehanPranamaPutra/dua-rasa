<?php

namespace App\Http\Controllers\user;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use function Symfony\Component\Clock\now;

class PaymentController extends Controller
{
   public function handleCallback(Request $request)
    {
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        try {
            $notif = new \Midtrans\Notification();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid Notification'], 400);
        }

        // 1. Validasi Signature Key (Keamanan Standard)
        $validSignatureKey = hash("sha512", $notif->order_id . $notif->status_code . $notif->gross_amount . config('midtrans.server_key'));
        if ($notif->signature_key !== $validSignatureKey) {
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        // 2. Cek Order Exist
        $order = Order::where('invoice_number', $orderId)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 3. LOGIKA PENERJEMAH (MAPPING)
        // Midtrans (Inggris) -> Database Tim (Indonesia Sesuai Enum)
        // Enum DB: ['Pending','Berhasil','Gagal','Expired','Refound']

        $statusUntukDb = 'Pending'; // Default awal

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $statusUntukDb = 'Pending';
                } else {
                    $statusUntukDb = 'Berhasil';
                }
            }
        } elseif ($transaction == 'settlement') {
            $statusUntukDb = 'Berhasil';
        } elseif ($transaction == 'pending') {
            $statusUntukDb = 'Pending';
        } elseif ($transaction == 'deny') {
            $statusUntukDb = 'Gagal';
        } elseif ($transaction == 'expire') {
            $statusUntukDb = 'Expired';
        } elseif ($transaction == 'cancel') {
            $statusUntukDb = 'Gagal';
        } elseif ($transaction == 'refund') {
            // Perhatikan ejaan di DB Anda 'Refound' (Typo di schema, tapi kita harus ikut DB)
            $statusUntukDb = 'Refound';
        }

        // 4. Simpan ke Database
        $this->updateOrderStatus($order, $statusUntukDb, $notif);

        return response()->json(['message' => 'Callback received successfully']);
    }

    protected function updateOrderStatus(Order $order, string $statusDb, $notif)
    {
        DB::beginTransaction();
        try {
            // A. Update Order
            // Kita ubah payment_status sesuai mapping tadi
            $dataUpdateOrder = ['payment_status' => $statusDb];

            // Opsional: Jika tim setuju, ubah order_status jadi 'processing' jika sudah bayar
            if ($statusDb === 'Berhasil') {
                $dataUpdateOrder['order_status'] = 'processing';
            }

            $order->update($dataUpdateOrder);

            // B. Update/Create Payment Record
            // Kita isi kolom wajib sesuai Schema yang ada
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    // Kolom 'method' wajib diisi (string), kita ambil dari tipe pembayaran Midtrans
                    'method'           => $notif->payment_type ?? 'unknown',

                    // Kolom 'transaction_code' nullable
                    'transaction_code' => $notif->transaction_id,

                    'amount'           => $notif->gross_amount,

                    // Ini status yang sudah diterjemahkan ke Bhs Indonesia
                    'payment_status'   => $statusDb,

                    // Isi waktu hanya jika berhasil
                    'payment_time'     => ($statusDb === 'Berhasil') ? now() : null,
                ]
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Payment Error: " . $e->getMessage());
        }
    }
}
