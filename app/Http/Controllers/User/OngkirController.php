<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class OngkirController extends Controller
{
    public function calculate(Request $request)
    {
        try {
            $apiKey = config('rajaongkir.api_key');
            // Gunakan endpoint domestic-cost (Komerce)
            $baseUrl = "https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost";

            $weight = ceil((float) $request->weight);
            if ($weight < 1) $weight = 1000;

            $response = Http::asForm()->withHeaders([
                'key' => $apiKey,
            ])->post($baseUrl, [
                // PENTING: Gunakan ID Kecamatan untuk origin & destination
                // Jika Anda tetap ingin pakai Jakarta Barat, cari ID Kecamatannya (misal: 2102 untuk Kebon Jeruk)
                'origin'      => 4374,
                'destination' => $request->destination, // Pastikan ini ID Kecamatan
                'weight'      => (int) $weight,
                'courier'     => strtolower($request->courier),
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['data'])) {
                $costs = $data['data']; // Komerce V2 menggunakan key 'data'
                $formattedCosts = [];
                foreach ($costs as $c) {
                    $formattedCosts[] = [
                        'service'     => $c['service'],
                        'description' => $c['description'],
                        'value'       => $c['cost'], // Di V2 biasanya langsung 'cost'
                        'etd'         => $c['etd']
                    ];
                }
                return response()->json($formattedCosts);
            }

            return response()->json(['message' => 'Layanan tidak ditemukan untuk rute ini.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
