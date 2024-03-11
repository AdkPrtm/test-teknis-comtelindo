<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Macbook Air M1 8GB 256GB',
                'description' => 'Garansi Resmi Indonesia',
                'category' => 'Elekronik',
                'price' => 11000000,
                'stock' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Macbook Pro M1 8GB 256GB',
                'description' => 'Garansi Resmi Indonesia',
                'category' => 'Elekronik',
                'price' => 14000000,
                'stock' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Macbook Air M2 8GB 256GB',
                'description' => 'Garansi Resmi Indonesia',
                'category' => 'Elekronik',
                'price' => 17000000,
                'stock' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'Macbook Pro M2 8GB 256GB',
                'description' => 'Garansi Resmi Indonesia',
                'category' => 'Elekronik',
                'price' => 19000000,
                'stock' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
