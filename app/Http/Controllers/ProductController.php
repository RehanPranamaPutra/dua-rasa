<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($id)
{


    $product = Product::findOrFail($id);
    $otherProducts = Product::where('id', '!=', $id)->take(8)->get();

    return view('public.detailProduc', compact('product','otherProducts'));
}
}
