<?php

namespace Database\Seeders;

use App\Models\UserCustomer;
use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $customers = UserCustomer::all();

        if ($customers->isEmpty()) {
            $this->command->error("Seeder gagal: tidak ada data user_customers!");
            return;
        }

        // Daftar kota valid RajaOngkir
        $cities = [
            [
                'province' => 'Jawa Barat',
                'city' => 'Bandung',
                'rajaongkir_city_id' => 23,
            ],
            [
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'rajaongkir_city_id' => 152,
            ],
            [
                'province' => 'Jawa Timur',
                'city' => 'Surabaya',
                'rajaongkir_city_id' => 444,
            ],
        ];

        foreach ($customers as $customer) {

            $count = fake()->numberBetween(1, 2);

            for ($i = 0; $i < $count; $i++) {

                $city = collect($cities)->random();

                Address::create([
                    'customer_id'          => $customer->id,
                    'customer_name'        => $customer->name,
                    'no_telp'              => fake()->phoneNumber(),
                    'province'             => $city['province'],
                    'city'                 => $city['city'],
                    'rajaongkir_city_id'   => $city['rajaongkir_city_id'],
                    'subdistrict'          => 'Kecamatan Contoh',
                    'village'              => 'Kelurahan Contoh',
                    'postal_code'          => fake()->postcode(),
                    'specific_address'     => fake()->streetAddress(),
                ]);
            }
        }

        $this->command->info("Seeder alamat berhasil (RajaOngkir ready) ✅");
    }
}
