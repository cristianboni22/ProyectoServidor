<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class EmpleadoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('empleado')->insert([
            [
                'dni' => '12345678A',
                'nombre_completo' => 'Juan Pérez',
                'login' => 'juanperez',
                'password' => bcrypt('password123'),
                'departamento_id' => 1,
            ],
            [
                'dni' => '23456789B',
                'nombre_completo' => 'Ana Gómez',
                'login' => 'anagomez',
                'password' => bcrypt('password456'),
                'departamento_id' => 2,
            ],
            [
                'dni' => '11111111111',
                'nombre_completo' => 'SuperAdmin',
                'login' => 'SuperAdmin',
                'password' => bcrypt('password'),
                'departamento_id' => 1,
            ],
            [
                'dni' => '34567890C',
                'nombre_completo' => 'Carlos Ruiz',
                'login' => 'carlosruiz',
                'password' => bcrypt('password789'),
                'departamento_id' => 3, 
            ],
        ]);
    }
}
