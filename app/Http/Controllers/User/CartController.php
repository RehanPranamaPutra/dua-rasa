<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Show all cart items
     */
    public function index()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $userId = Auth::guard('customer')->id();

        $cartItems = Cart::with('product')
            ->where('customer_id', $userId)
            ->get();

        $total = $cartItems->sum(function ($item) {
            return ($item->product->price ?? 0) * $item->quantity;
        });

        return view('user.cart', compact('cartItems', 'total'));
    }

    /**
     * Tambah item ke keranjang
     */
    public function store(Request $request)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login')
                ->with('error', 'Silakan login terlebih dahulu!');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = Auth::guard('customer')->id();
        $productId = $request->product_id;

        $cart = Cart::where('customer_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cart) {
            $cart->increment('quantity');
        } else {
            Cart::create([
                'customer_id' => $userId,
                'product_id'       => $productId,
                'quantity'         => 1,
            ]);
        }

        return redirect()
            ->route('user.cart.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Hapus produk dari keranjang
     */
    public function remove($productId)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login')
                ->with('error', 'Silakan login terlebih dahulu!');
        }

        $userId = Auth::guard('customer')->id();

        Cart::where('customer_id', $userId)
            ->where('product_id', $productId)
            ->delete();

        return redirect()
            ->route('user.cart.index')
            ->with('success', 'Produk berhasil dihapus dari keranjang!');
    }
}
