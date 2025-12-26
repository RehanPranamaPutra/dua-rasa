<?php

namespace App\Http\Controllers\User;

use Midtrans\Snap;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Address;
use App\Models\Payment;
use App\Models\OrderDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function index()
    {
        // 1. Cek Login Customer
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login')->with('error', 'Login dulu.');
        }

        // 2. Ambil User dari Guard Customer
        $user = Auth::guard('customer')->user();

        // 3. Ambil Keranjang berdasarkan customer_id
        $cartItems = Cart::with('product')
            ->where('customer_id', $user->id)
            ->get();

        // 4. Cek apakah kosong
        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // 5. Ambil Alamat
        $addresses = Address::where('customer_id', $user->id)->get();

        return view('user.checkout.index', compact('addresses', 'cartItems'));
    }

    // PROSES SIMPAN ORDER
    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        // Gunakan Guard Customer
        $user = Auth::guard('customer')->user();

        $cartItems = Cart::with('product')
            ->where('customer_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $shippingCost = 10000;
        $totalPrice = $subtotal + $shippingCost;

        try {
            DB::beginTransaction();

            // Create Order
            $order = Order::create([
                'customer_id'    => $user->id, // ID Customer
                'address_id'     => $request->address_id,
                'invoice_number' => "INV" . date('Ymd') . rand(1000, 9999),
                'total_price'    => $totalPrice,
                'shipping_cost'  => $shippingCost,
                'order_status'   => 'new',
            ]);

            // Create Details
            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'address_id'   => $request->address_id,
                    'product_name' => $item->product->name,
                    'price'        => $item->product->price,
                    'amount'       => $item->quantity,
                    'total'        => $item->product->price * $item->quantity,
                ]);
            }

            // Hapus Keranjang milik Customer ini
            //Cart::where('customer_id', $user->id)->delete();

            DB::commit();

           return redirect()->route('customer.orders.show', $order->invoice_number)
    ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // FUNCTION SHOW (Detail)
    public function show($invoice) // Parameter ganti jadi $invoice
    {
        // 1. Ambil Order berdasarkan invoice_number
        $order = Order::with(['details.product', 'address', 'payment'])
            ->where('invoice_number', $invoice) // Ganti 'id' menjadi 'invoice_number'
            ->where('customer_id', Auth::guard('customer')->id())
            ->firstOrFail();

        // 2. Cek Payment & Token (Logic tetap sama)
        $payment = $order->payment;
        $snapToken = null;

        if ($payment && !empty($payment->snap_token)) {
            $snapToken = $payment->snap_token;
        }

        // 3. Request ke Midtrans jika token belum ada
        if (empty($snapToken)) {

            // --- LOGIC PERHITUNGAN GROSS AMOUNT ---
            // Pastikan perhitungan ini konsisten.
            // Berdasarkan kode sebelumnya, $order->total_price di DB sudah termasuk ongkir.
            // Tapi untuk Midtrans, kita harus rinci item + ongkir.

            $item_details = [];
            foreach ($order->details as $detail) {
                $item_details[] = [
                    'id'       => $detail->product_id,
                    'price'    => (int) $detail->price,
                    'quantity' => (int) $detail->amount,
                    'name'     => substr($detail->product->name, 0, 50)
                ];
            }

            if ($order->shipping_cost > 0) {
                $item_details[] = [
                    'id'       => 'SHIPPING',
                    'price'    => (int) $order->shipping_cost,
                    'quantity' => 1,
                    'name'     => 'Biaya Pengiriman'
                ];
            }

            // Hitung ulang gross_amount dari item_details agar akurat
            $gross_amount = 0;
            foreach ($item_details as $item) {
                $gross_amount += ($item['price'] * $item['quantity']);
            }

            $user = Auth::guard('customer')->user();
            $address = $order->address;

            $customer_details = [
                'first_name'    => $user->name,
                'email'         => $user->email,
                'phone'         => $address->no_telp ?? $user->no_hp ?? '08123456789',
            ];

            $payload = [
                'transaction_details' => [
                    'order_id'     => $order->invoice_number, // Midtrans pakai Invoice Number
                    'gross_amount' => $gross_amount,
                ],
                'customer_details' => $customer_details,
                'item_details'     => $item_details,
            ];

            try {
                $serverKey = config('midtrans.server_key');
                $response = Http::withBasicAuth($serverKey, '')
                    ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                    ->withoutVerifying()
                    ->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $payload);

                if ($response->successful()) {
                    $snapToken = $response->json()['token'];

                    // Simpan Token
                    Payment::updateOrCreate(
                        ['order_id' => $order->id], // Disini TETAP pakai ID untuk relasi database
                        [
                            'amount'           => $gross_amount,
                            'payment_status'   => 'Pending',
                            'transaction_code' => $snapToken,
                            'method'           => 'Midtrans Snap',
                            // 'snap_token'    => $snapToken // Pastikan kolom ini ada di DB atau pakai transaction_code
                        ]
                    );
                } else {
                    return back()->with('error', 'Gagal request ke payment gateway');
                }
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        return view('user.checkout.show', compact('order', 'snapToken'));
    }
}
