<?php

namespace App\Http\Controllers\User;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravolt\Indonesia\Models\Provinsi;
use Laravolt\Indonesia\Models\Kabupaten;
use Laravolt\Indonesia\Models\Kecamatan;
use Laravolt\Indonesia\Models\Kelurahan;

class AddressController extends Controller
{
    public function create()
    {
        // Mengambil semua data Provinsi dari package laravolt/indonesia
        $provinces = Provinsi::all();

        // Mengembalikan view dengan data Provinsi
        return view('user.address.create', compact('provinces'));
    }

    /**
     * Endpoint API untuk mengambil Kabupaten/Kota berdasarkan ID Provinsi.
     * Dipanggil menggunakan AJAX.
     */
    // UNTUK CITY (Provinsi -> Kota)
    public function cities(Request $request)
    {
        $provinceCode = $request->get('province_id');

        // MENGGUNAKAN Model Kabupaten
        // Mencari dengan 'province_code'
        $cities = Kabupaten::where('province_code', $provinceCode)->get();

        return response()->json($cities);
    }

    // UNTUK DISTRICT (Kota -> Kecamatan)
    public function districts(Request $request)
    {
        // Nilai yang dikirim dari frontend (value dari dropdown Kabupaten/Kota)
        $cityCode = $request->get('city_id');

        // MENGGUNAKAN Model Kecamatan dan Mencari berdasarkan 'city_code'
        // Model Kecamatan extend District, yang menggunakan city_code
        $districts = Kecamatan::where('city_code', $cityCode)->get();

        // Pastikan data Kecamatan ada untuk Kode Kabupaten/Kota yang dipilih
        // Jika $districts kosong, ini penyebab dropdown tidak terisi.

        return response()->json($districts);
    }

    public function villages(Request $request)
    {
        // Menggunakan 'district_code' untuk mencari di Model Kelurahan
        $districtCode = $request->get('district_id');
        $villages = Kelurahan::where('district_code', $districtCode)->get();
        return response()->json($villages);
    }
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'subdistrict' => 'required|string|max:255',
            'village' => 'required|string|max:255',
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
            'village' => $request->village,
            'postal_code' => $request->postal_code,
            'specific_address' => $request->specific_address,
        ]);

        return redirect()->route('customer.checkout');
    }

    public function edit($id)
    {
        $provinces = Provinsi::all();
        $address = Address::where('customer_id', Auth()->id())
            ->where('id', $id)
            ->firstOrFail();
        return view('user.address.edit', compact('address', 'provinces'));
    }

    public function update(Request $request, Address $address)
    {

        $address->customer_name = $request->customer_name;
        $address->no_telp = $request->no_telp;
        $address->province = $request->province;
        $address->city = $request->city;
        $address->subdistrict = $request->subdistrict;
        $address->village = $request->village;
        $address->postal_code = $request->postal_code;
        $address->specific_address = $request->specific_address;


        $address->save();
        return redirect()->route('customer.checkout')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function delete(Address $address)
    {
        $address->delete();
        return redirect()->route('customer.checkout')->with('success','Berhasil menghapus alamar');
    }
}
