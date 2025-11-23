<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Laptop HP Pavilion', 'price' => 899.99, 'stock' => 15, 'category_id' => 1],
            ['name' => 'Wireless Mouse', 'price' => 25.50, 'stock' => 50, 'category_id' => 1],
            ['name' => 'T-Shirt Cotton', 'price' => 19.99, 'stock' => 100, 'category_id' => 2],
            ['name' => 'Organic Coffee Beans', 'price' => 12.99, 'stock' => 30, 'category_id' => 3],
            ['name' => 'Garden Hose 50ft', 'price' => 35.00, 'stock' => 20, 'category_id' => 4],
            ['name' => 'Basketball', 'price' => 29.99, 'stock' => 8, 'category_id' => 5],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
