<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Minuman', 'Makanan', 'Elektronik', 'Fashion', 'Kesehatan'];

        foreach ($categories as $name) {
            Categories::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => 'Kategori untuk ' . strtolower($name),
            ]);
        }
    }
}
