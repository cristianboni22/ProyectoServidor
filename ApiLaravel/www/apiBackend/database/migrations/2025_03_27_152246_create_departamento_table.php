<?php

use Illuminate\Database\Migrations\Migration; // Importa la clase Migration, la clase base para todas las migraciones de Laravel.
use Illuminate\Database\Schema\Blueprint; // Importa la clase Blueprint, que se utiliza para definir la estructura de las tablas de la base de datos.
use Illuminate\Support\Facades\Schema; // Importa la clase Schema, que proporciona métodos para interactuar con el esquema de la base de datos.

return new class extends Migration // Define una nueva clase anónima que extiende la clase Migration.
{
    /**
     * Run the migrations.
     */
    public function up(): void // Este método se ejecuta cuando se aplica la migración (por ejemplo, con el comando 'php artisan migrate').
    {
        Schema::create('departamento', function (Blueprint $table) {
            // Crea una nueva tabla llamada 'departamento'.
            // El primer argumento de 'create' es el nombre de la tabla, y el segundo es una función anónima que recibe un objeto Blueprint.
            $table->id(); // Crea una columna llamada 'id' que es una clave primaria autoincremental (BIGINT UNSIGNED).
            $table->timestamps(); // Crea dos columnas: 'created_at' y 'updated_at', que almacenan la fecha y hora de creación y última modificación del registro.
            $table->string('nombre'); // Crea una columna llamada 'nombre' de tipo VARCHAR.  El tamaño de la cadena se define por defecto en 255 caracteres.
            $table->string('telefono'); // Crea una columna llamada 'telefono' de tipo VARCHAR.
            $table->string('email'); // Crea una columna llamada 'email' de tipo VARCHAR.

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Este método se ejecuta cuando se revierte la migración (por ejemplo, con el comando 'php artisan migrate:rollback').
    {
        Schema::dropIfExists('departamento'); // Elimina la tabla 'departamento' si existe.  Esto deshace los cambios realizados por el método 'up'.
    }
};
