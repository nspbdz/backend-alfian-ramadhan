<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Order 1
        $order = Order::create([
            'user_id' => 3, // Customer 1
            'total_price' => 60000,
            'discount' => 6000,
            'shipping_cost' => 0,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => 1, // Produk A
            'quantity' => 3,
            'price' => 60000, // 3 x 20000
        ]);

        // Order 2
        $order = Order::create([
            'user_id' => 4, // Customer 2
            'total_price' => 120000,
            'discount' => 12000,
            'shipping_cost' => 0,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => 2, // Produk B
            'quantity' => 2,
            'price' => 100000, // 2 x 50000
        ]);
    }
}