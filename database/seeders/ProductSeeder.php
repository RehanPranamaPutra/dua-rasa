<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Categories::all();

        foreach ($categories as $category) {
            for ($i = 1; $i <= 5; $i++) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $category->name . ' ' . $i,
                    'description' => 'Deskripsi produk ' . $category->name . ' nomor ' . $i,
                    'price' => rand(10000, 100000),
                    'stok' => rand(10, 100),
                    'weight' => rand(100, 1000) / 100,
                    'status' => 'aktif',
                ]);
            }
        }
    }
}
