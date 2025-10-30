<?php

namespace App\Http\Controllers\User;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
     // Halaman tambah alamat
    public function create()
    {
        return view('user.address.index');
    }

    // Simpan alamat baru
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'subdistrict' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'specific_address' => 'required|string|max:255',
        ]);

        Address::create([
            'customer_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'no_telp' => $request->no_telp,
            'province' => $request->province,
            'city' => $request->city,
            'subdistrict' => $request->subdistrict,
            'postal_code' => $request->postal_code,
            'specific_address' => $request->specific_address,
        ]);

        return redirect()->route('checkout')->with('success', 'Alamat berhasil ditambahkan!');
    }
}
