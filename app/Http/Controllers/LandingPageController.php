<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    public function index()
    {
        // Ambil semua data produk dari database
        $products = Product::all();
        $userId = Auth::guard('customer')->id();

        $cartItems = $userId ? Cart::where('customer_id', $userId)->get() : collect();

        // SALAH: return view('layouts.public', ...);
        // BENAR: Panggil file isinya (misal: landingPage)
        return view('public.landingPage', compact('products', 'cartItems'));
        
    }
}
