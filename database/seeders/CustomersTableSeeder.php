<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Customers;
use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Customers::factory(10)->create();

        Customers::factory()->create([
            'dni'=> '12345678',
            'id_reg'=> 1,
            'id_com'=> 3,
            'name' => "Monica",
            'email' => 'monica@gmail.com',
            'last_name'=> 'Rodriguez',
            'address'=> 'Mi casa',
            'date_reg' => now(),
        ]);
    }
}
