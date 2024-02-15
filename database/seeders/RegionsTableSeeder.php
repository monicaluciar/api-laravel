<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Regions;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $regions = [
            ['description' => 'Región Metropolitana'],
            ['description' => 'Región de Valparaíso'],
            // Agrega más regiones aquí...
        ];

        Regions::insert($regions);
    }
}
