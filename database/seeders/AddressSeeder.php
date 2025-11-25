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

        if ($customers->count() == 0) {
            $this->command->error("Seeder gagal: tidak ada data user_customers!");
            return;
        }

        foreach ($customers as $customer) {
            // Setiap customer kita buat 1–3 alamat
            $count = fake()->numberBetween(1, 3);

            for ($i = 1; $i <= $count; $i++) {
                Address::create([
                    'customer_id'      => $customer->id,
                    'customer_name'    => $customer->name,
                    'no_telp'          => fake()->phoneNumber(),
                    'province'         => fake()->state(),
                    'city'             => fake()->city(),
                    'subdistrict'      => fake()->citySuffix(),
                    'village'          => fake()->streetName(),
                    'postal_code'      => fake()->postcode(),
                    'specific_address' => fake()->address(),
                ]);
            }
        }
    }
}
