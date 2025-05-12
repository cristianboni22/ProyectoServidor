<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empleado', function (Blueprint $table) {
            $table->string('dni')->primary();  // Crea una columna 'dni' de tipo string y la define como clave primaria.
            $table->timestamps();             // Crea las columnas 'created_at' y 'updated_at' para el control de tiempo.
            $table->rememberToken();          // Crea una columna 'remember_token' para almacenar tokens de "recordarme".
            $table->string('nombre_completo'); // Crea una columna 'nombre_completo' de tipo string.
            $table->string('login');           // Crea una columna 'login' de tipo string.
            $table->string('password');        // Crea una columna 'password' de tipo string (¡CUIDADO! Las contraseñas SIEMPRE deben almacenarse cifradas, no como texto plano).
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleado');      // Elimina la tabla 'empleado' si existe, al revertir la migración.
    }
};
