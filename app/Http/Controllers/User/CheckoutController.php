<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_customer_id', Auth::id())
            ->get();

        $addresses = Address::where('customer_id', Auth::id())->get();

        return view('user.checkout.index', compact('cartItems', 'addresses'));
    }

    public function  store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        return redirect()->route('orders.success')->with('success', 'Pesanan berhasil dibuat!');
    }
}
