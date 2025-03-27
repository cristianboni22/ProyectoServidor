<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
