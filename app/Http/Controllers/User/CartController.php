<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        // Ambil semua item cart milik user yang login
        $cartItems = Cart::with('product')
            ->where('user_customer_id', Auth::guard('customer')->id())
            ->get();

        // Hitung total harga semua item
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('user.cart', compact('cartItems', 'total'));
    }

    public function add($productId)
    {
        $userId = Auth::guard('customer')->id();

        $cart = Cart::where('user_customer_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cart) {
            $cart->quantity += 1;
            $cart->save();
        } else {
            Cart::create([
                'user_customer_id' => $userId,
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }

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
