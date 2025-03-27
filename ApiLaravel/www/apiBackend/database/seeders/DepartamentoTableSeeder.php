<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class DepartamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departamento')->insert([
            [
                'nombre' => 'Recursos Humanos',
                'telefono' => '555-1234',
                'email' => 'rh@empresa.com',
            ],
            [
                'nombre' => 'IT',
                'telefono' => '555-5678',
                'email' => 'it@empresa.com',
            ],
            [
                'nombre' => 'Marketing',
                'telefono' => '555-9876',
                'email' => 'marketing@empresa.com',
            ],
        ]);
    }
}
