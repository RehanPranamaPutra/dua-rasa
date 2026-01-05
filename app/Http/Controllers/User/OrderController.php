<?php

namespace App\Http\Controllers\User;

use Midtrans\Snap;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Address;
use App\Models\Payment;
use App\Models\OrderDetail;
use Illuminate\Support\Str;
use Midtrans\Config;
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
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login')->with('error', 'Login dulu.');
        }

        $user = Auth::guard('customer')->user();

        // Mengambil item keranjang (Pastikan menggunakan 'amount' sesuai database)
        $cartItems = Cart::with('product')
            ->where('customer_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $addresses = Address::where('customer_id', $user->id)->get();

        return view('user.checkout.index', compact('addresses', 'cartItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'shipping_service' => 'required|string|max:100',
            'shipping_cost' => 'required|integer|min:0',
        ]);

        $user = Auth::guard('customer')->user();
        $cartItems = Cart::with('product')->where('customer_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong.');
        }

        // HITUNG TOTAL (Gunakan 'amount')
        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->amount);
        $shippingCost = (int) $request->shipping_cost;
        $totalPrice = $subtotal + $shippingCost;

        try {
            DB::beginTransaction();

            $order = Order::create([
                'customer_id'      => $user->id,
                'address_id'       => $request->address_id,
                'invoice_number'   => "INV" . date('Ymd') . rand(1000, 9999),
                'total_price'      => $totalPrice,
                'shipping_service' => $request->shipping_service,
                'shipping_cost'    => $shippingCost,
                'order_status'     => 'new',
                'payment_status'   => 'Pending',
            ]);

            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'address_id'   => $request->address_id,
                    'product_name' => $item->product->name,
                    'price'        => $item->product->price,
                    'amount'       => $item->amount, // Disamakan
                    'total'        => $item->product->price * $item->amount,
                ]);
            }

            Cart::where('customer_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->invoice_number)
                ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($invoice)
    {
        $order = Order::with(['details.product', 'address', 'payment'])
            ->where('invoice_number', $invoice)
            ->where('customer_id', Auth::guard('customer')->id())
            ->firstOrFail();

        // Cek status ke Midtrans jika masih pending
        if ($order->payment_status == 'Pending') {
            try {
                $serverKey = config('midtrans.server_key');
                $url = config('midtrans.is_production')
                    ? "https://api.midtrans.com/v2/{$invoice}/status"
                    : "https://api.sandbox.midtrans.com/v2/{$invoice}/status";

                $response = Http::withBasicAuth($serverKey, '')->get($url);

                if ($response->successful()) {
                    $res = $response->json();
                    $trStatus = $res['transaction_status'] ?? '';

                    if (in_array($trStatus, ['settlement', 'capture', 'success'])) {
                        $order->update(['payment_status' => 'Berhasil', 'order_status' => 'processing']);
                        Payment::updateOrCreate(
                            ['order_id' => $order->id],
                            ['payment_status' => 'Berhasil', 'payment_time' => now(), 'method' => $res['payment_type'] ?? 'Midtrans Snap']
                        );
                        $order->refresh();
                    }
                }
            } catch (\Exception $e) {
                Log::error("Gagal cek status manual: " . $e->getMessage());
            }
        }

        $payment = $order->payment;
        $snapToken = ($payment && $order->payment_status !== 'Berhasil') ? $payment->transaction_code : null;

        if (empty($snapToken) && $order->payment_status == 'Pending') {
            try {
                Config::$serverKey = config('midtrans.server_key');
                Config::$isProduction = config('midtrans.is_production');
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $payload = [
                    'transaction_details' => [
                        'order_id'     => $order->invoice_number,
                        'gross_amount' => (int) $order->total_price,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::guard('customer')->user()->name,
                        'email'      => Auth::guard('customer')->user()->email,
                    ],
                ];

                $snapToken = Snap::getSnapToken($payload);

                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'amount'           => $order->total_price,
                        'payment_status'   => 'Pending',
                        'transaction_code' => $snapToken,
                        'method'           => 'Midtrans Snap',
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Snap Token Error: " . $e->getMessage());
            }
        }

        return view('user.checkout.show', compact('order', 'snapToken'));
    }


    public function history()
    {
        // 1. Cek Login
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login');
        }

        $user = Auth::guard('customer')->user();

        // 2. Ambil data order dengan pagination agar tidak berat
        $orders = Order::where('customer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.checkout.history', compact('orders'));
    }


    public function checkStatusPayment(Request $request)
    {
        $user = Auth::guard('customer')->user();

        $query = Order::where('customer_id', $user->id);

        if ($request->has('status') && $request->status != 'all') {
            $status = $request->status;

            // Cek payment status
            if (in_array($status, ['Pending', 'Berhasil', 'Gagal', 'Expired', 'Refound'])) {
                $query->where('payment_status', $status);
            }
            // Cek order status
            elseif (in_array($status, ['new', 'processing', 'shipped', 'delivered', 'cancelled'])) {
                $query->where('order_status', $status);
            }
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(6)->withQueryString();

        return view('user.checkout.history', compact('orders'));
    }
}
