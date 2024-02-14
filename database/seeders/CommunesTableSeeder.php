<?php

namespace Database\Seeders;

use App\Models\Communes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommunesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $communes = [
            ['id_reg'=>1,'description' => 'Santiago'],
            ['id_reg'=>1,'description' => 'Cerrillos'],
            ['id_reg'=>1,'description' => 'Cerro Navia'],
            ['id_reg'=>1,'description' => 'Conchalí'],
            ['id_reg'=>1,'description' => 'El Bosque'],
            ['id_reg'=>1,'description' => 'Estación Central'],
            ['id_reg'=>1,'description' => 'Huechuraba'],
            ['id_reg'=>1,'description' => 'Independencia'],
            ['id_reg'=>1,'description' => 'La Cisterna'],
            ['id_reg'=>1,'description' => 'La Florida'],
            ['id_reg'=>2,'description' => 'Valparaíso'],
            ['id_reg'=>2,'description' => 'Casablanca'],
            ['id_reg'=>2,'description' => 'Concón'],
            ['id_reg'=>2,'description' => 'Juan Fernández'],
            ['id_reg'=>2,'description' => 'Puchuncaví'],
            ['id_reg'=>2,'description' => 'Quintero'],
            ['id_reg'=>2,'description' => 'Viña del Mar']
        ];

        Communes::insert($communes);
    }
}
