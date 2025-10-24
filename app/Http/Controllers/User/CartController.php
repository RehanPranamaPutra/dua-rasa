<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::guard('customer')->id();

        // Ambil semua item di keranjang user
        $cartItems = Cart::with('product')
            ->where('user_customer_id', $userId)
            ->get();

        // Hitung total harga
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('user.cart', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $userId = Auth::guard('customer')->id();

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Cek apakah produk sudah ada di keranjang user
        $cartItem = Cart::where('user_customer_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Jika sudah ada, tambah quantity
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            // Jika belum ada, buat baru
            Cart::create([
                'user_customer_id' => $userId,
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        // Setelah tambah produk, langsung redirect ke halaman cart
        return redirect()->route('user.cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function remove($productId)
    {
        $userId = Auth::guard('customer')->id();

        Cart::where('user_customer_id', $userId)
            ->where('product_id', $productId)
            ->delete();

        return redirect()->route('user.cart.index')->with('success', 'Produk dihapus dari keranjang!');
    }
}
