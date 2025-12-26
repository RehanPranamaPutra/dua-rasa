<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\UserCustomer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Pastikan ada minimal 1 user customer
        $user = UserCustomer::first() ?? UserCustomer::create([
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
        ]);

        // Pastikan ada beberapa produk
        if (Product::count() === 0) {
            Product::insert([
                [
                    'name' => 'Nasi Goreng Spesial',
                    'price' => 25000,
                    'description' => 'Nasi goreng dengan topping ayam dan telur.',
                    'image' => 'nasi-goreng.jpg',
                    'status' => 'Tersedia',
                    'stok' => 10,
                ],
                [
                    'name' => 'Ayam Geprek',
                    'price' => 20000,
                    'description' => 'Ayam goreng crispy dengan sambal pedas.',
                    'image' => 'ayam-geprek.jpg',
                    'status' => 'Tersedia',
                    'stok' => 15,
                ],
                [
                    'name' => 'Mie Ayam Bakso',
                    'price' => 18000,
                    'description' => 'Mie ayam disajikan dengan bakso sapi dan sawi hijau.',
                    'image' => 'mie-ayam-bakso.jpg',
                    'status' => 'Tersedia',
                    'stok' => 20,
                ],
            ]);
        }

        // Ambil beberapa produk yang sudah ada
        $products = Product::take(3)->get();

        // Hapus data lama di tabel cart
        Cart::truncate();

        // Tambahkan contoh data cart
        foreach ($products as $index => $product) {
            Cart::create([
                'customer_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $index + 1, // contoh variasi quantity
            ]);
        }

        $this->command->info('✅ CartSeeder berhasil dijalankan.');
    }
}
