<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'product_id' => 3,
                'user_id' => 1,
                'order_code' => 'BXSOQUD502',
                'quantity' => 2,
                'status' => 'Proccess'
            ],
            [
                'product_id' => 1,
                'user_id' => 1,
                'order_code' => '4DAJHSU2K5',
                'quantity' => 2,
                'status' => 'Proccess'
            ],
            [
                'product_id' => 4,
                'user_id' => 1,
                'order_code' => 'STKV83UZ78',
                'quantity' => 2,
                'status' => 'Proccess'
            ],
            [
                'product_id' => 2,
                'user_id' => 1,
                'order_code' => '5NSGX74CUU',
                'quantity' => 2,
                'status' => 'Proccess'
            ],

        ]);
    }
}
