<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('dni')->unique();
            $table->string('nombre_completo');
            $table->string('login')->unique();
            $table->string('password');
            $table->unsignedBigInteger('id_departamento')->nullable(); // Lo hace opcional
            $table->timestamps();
    
            // Si tienes una relación con departamentos, la puedes definir así:
            $table->foreign('id_departamento')->references('id')->on('departamentos')->onDelete('set null');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleado');
    }
};
