<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class LandingPageController extends Controller
{
    public function index()
    {
        // Ambil semua data produk dari database
        $products = Product::all();

        // Kirim ke view landingPage.blade.php
        return view('landingPage', compact('products'));
    }
}
