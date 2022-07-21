<?php

namespace Database\Seeders;

use App\Models\ProductRawMaterial;
use App\Models\RawMaterial;
use App\Models\Warehouse;
use Database\Seeders\RawMaterialSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RawMaterialSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(WarehouseSeeder::class);
        $this->call(ProductRawMaterialSeeder::class);
    }
}
