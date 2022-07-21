<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'name' => 'ko`ylak',
                'code' => '1402'
            ],
            [
                'name' => 'shim',
                'code' => '1403'
            ]
        ];

        foreach ($products as $product){
            Product::create($product);
        }
    }
}
