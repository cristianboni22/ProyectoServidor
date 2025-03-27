<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('empleado')->insert([
            [
                'dni' => '12345678A',
                'nombre_completo' => 'Juan Pérez',
                'login' => 'juanperez',
                'password' => bcrypt('password123'),
                'id_departamento' => 1,
            ],
            [
                'dni' => '23456789B',
                'nombre_completo' => 'Ana Gómez',
                'login' => 'anagomez',
                'password' => bcrypt('password456'),
                'id_departamento' => 2,
            ],
            [
                'dni' => '34567890C',
                'nombre_completo' => 'Carlos Ruiz',
                'login' => 'carlosruiz',
                'password' => bcrypt('password789'),
                'id_departamento' => 3, 
            ],
        ]);
    }
}
