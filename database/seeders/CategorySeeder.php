<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'Electronics',
            'Clothing',
            'Home & Kitchen',
            'Books',
            'Sports & Outdoors',
            'Beauty & Personal Care',
            'Toys & Games',
            'Health',
            'Automotive',
            'Office Supplies',
        ] as $name) {
            Category::updateOrCreate(['name' => $name], ['status' => true]);
        }
    }
}
