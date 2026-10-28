<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Wireless Headphones', 'category' => 'Electronics', 'price' => 79.99, 'description' => 'Comfortable wireless headphones with clear sound.'],
            ['name' => 'Everyday T-Shirt', 'category' => 'Clothing', 'price' => 19.50, 'description' => 'A soft cotton T-shirt for everyday wear.'],
            ['name' => 'Ceramic Coffee Mug', 'category' => 'Home & Kitchen', 'price' => 12.00, 'description' => 'A durable ceramic mug for hot drinks.'],
            ['name' => 'Creative Writing Guide', 'category' => 'Books', 'price' => 24.95, 'description' => 'A practical guide to building writing skills.'],
            ['name' => 'Fitness Yoga Mat', 'category' => 'Sports & Outdoors', 'price' => 35.00, 'description' => 'A non-slip mat for yoga and floor exercises.'],
            ['name' => 'Daily Moisturizer', 'category' => 'Beauty & Personal Care', 'price' => 18.75, 'description' => 'A lightweight moisturizer for daily skincare.'],
            ['name' => 'Building Blocks Set', 'category' => 'Toys & Games', 'price' => 29.99, 'description' => 'A colorful building set for creative play.'],
            ['name' => 'Reusable Water Bottle', 'category' => 'Health', 'price' => 16.25, 'description' => 'A reusable bottle to keep drinks close at hand.'],
            ['name' => 'Car Phone Mount', 'category' => 'Automotive', 'price' => 21.00, 'description' => 'An adjustable mount for hands-free phone access.'],
            ['name' => 'Notebook Set', 'category' => 'Office Supplies', 'price' => 9.99, 'description' => 'A set of lined notebooks for notes and planning.'],
        ];

        foreach ($products as $product) {
            $category = Category::query()->where('name', $product['category'])->firstOrFail();
            $name = $product['name'];
            unset($product['name'], $product['category']);

            Product::updateOrCreate(
                ['name' => $name],
                [...$product, 'category_id' => $category->id, 'status' => true],
            );
        }
    }
}
