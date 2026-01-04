<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CheckoutController extends Controller
{
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

        return view('user.checkout.index', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        // Implementasi checkout store
        // Untuk sementara, redirect ke order
        return redirect()->route('customer.order')->with('success', 'Checkout berhasil.');
    }
}
