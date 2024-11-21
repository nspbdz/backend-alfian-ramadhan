<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'user_id' => 1, // Merchant 1
            'name' => 'Produk A',
            'price' => 20000,
            'stock' => 50,
        ]);

        Product::create([
            'user_id' => 1, // Merchant 1
            'name' => 'Produk B',
            'price' => 50000,
            'stock' => 30,
        ]);

        Product::create([
            'user_id' => 2, // Merchant 2
            'name' => 'Produk C',
            'price' => 10000,
            'stock' => 100,
        ]);

        Product::create([
            'user_id' => 2, // Merchant 2
            'name' => 'Produk D',
            'price' => 75000,
            'stock' => 20,
        ]);
    }
}