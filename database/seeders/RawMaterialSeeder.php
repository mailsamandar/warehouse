<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RawMaterial;

class RawMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['mato', 'ip', 'tugma', 'zamok'];

        foreach ($names as $name){
            RawMaterial::create(['name' => $name]);
        }
    }
}
