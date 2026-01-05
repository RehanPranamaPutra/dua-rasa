<?php

namespace App\Http\Controllers\User;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AddressController extends Controller
{
    // Gunakan Komerce untuk semua karena API Key Anda adalah API Key Komerce
    private $baseUrl = "https://rajaongkir.komerce.id/api/v1/destination";

    public function create()
    {
        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key'),
        ])->get($this->baseUrl . '/province');

        $provinces = $response->json()['data'] ?? [];
        return view('user.address.create', compact('provinces'));
    }

    public function getCities($provinceId)
    {
        $response = Http::withHeaders(['key' => config('rajaongkir.api_key')])
            ->get($this->baseUrl . "/city/{$provinceId}");

        // Komerce sudah mengembalikan format: data -> [[id, name], ...]
        // Langsung kembalikan agar JS bisa baca item.id dan item.name
        return response()->json($response->json()['data'] ?? []);
    }

    public function getDistricts($cityId)
    {
        $response = Http::withHeaders(['key' => config('rajaongkir.api_key')])
            ->get($this->baseUrl . "/district/{$cityId}");

        return response()->json($response->json()['data'] ?? []);
    }

    public function getVillages($districtId)
    {
        $response = Http::withHeaders(['key' => config('rajaongkir.api_key')])
            ->get($this->baseUrl . "/sub-district/{$districtId}");

        return response()->json($response->json()['data'] ?? []);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'    => 'required',
            'no_telp'          => 'required',
            'province_name'    => 'required',
            'subdistrict_id'          => 'required',
            'city_name'        => 'required',
            'subdistrict'      => 'required',
            'village'          => 'required',
            'postal_code'      => 'required',
            'specific_address' => 'required',
        ]);

        Address::create([
            'customer_id'        => Auth::id(),
            'customer_name'      => $request->customer_name,
            'no_telp'            => $request->no_telp,
            'province'           => $request->province_name,
            'city'               => $request->city_name,
            'rajaongkir_city_id' => $request->subdistrict_id, // ID 30 (Hulu Sungai Tengah versi Komerce)
            'subdistrict'        => $request->subdistrict,
            'village'            => $request->village,
            'postal_code'        => $request->postal_code,
            'specific_address'   => $request->specific_address,
        ]);

        return redirect()->route('customer.order')->with('success', 'Alamat berhasil disimpan');
    }
}
