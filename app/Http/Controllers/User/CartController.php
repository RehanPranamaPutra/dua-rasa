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


        $cartItems = Cart::where('customer_id', auth()->guard('customer')->id())->get();

        $cartCount = Cart::where('customer_id', auth()->guard('customer')->id())
            ->sum('amount');

        $total = Cart::where('customer_id', auth()->guard('customer')->id())
            ->with('product')
            ->get()
            ->sum(fn($item) => $item->product->price * $item->amount);

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
            'quantity' => 'nullable|integer|min:1',
        ]);

        $userId = Auth::guard('customer')->id();
        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        $product = \App\Models\Product::find($productId);
        $total = $product->price * $quantity;

        $cart = Cart::where('customer_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cart) {
            $cart->amount += $quantity;
            $cart->total += $total;
            $cart->save();
        } else {
            Cart::create([
                'customer_id' => $userId,
                'product_id' => $productId,
                'amount' => $quantity,
                'total' => $total,
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

    /**
     * Kosongkan keranjang
     */
    public function clear()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $userId = Auth::guard('customer')->id();

        Cart::where('customer_id', $userId)->delete();

        return redirect()
            ->route('user.cart.index')
            ->with('success', 'Keranjang berhasil dikosongkan!');
    }
}
