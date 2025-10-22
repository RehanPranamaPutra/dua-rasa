<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_customers')->insert([
            [
                'name' => 'Kenji Nakamura',
                'email' => 'kenji@example.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ayu Lestari',
                'email' => 'ayu@example.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081298765432',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rafi Pratama',
                'email' => 'rafi@example.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081377788899',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dewi Kartika',
                'email' => 'dewi@example.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081312345678',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bima Aditya',
                'email' => 'bima@example.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081355566677',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
