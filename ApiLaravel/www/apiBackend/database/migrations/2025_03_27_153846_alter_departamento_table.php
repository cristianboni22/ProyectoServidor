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
        //
        Schema::table('empleado', function (Blueprint $table) {
            // Alters the 'empleado' table.  This migration adds a new column and a foreign key constraint.
            $table->unsignedBigInteger('departamento_id')->nullable();
            // Adds a new column named 'departamento_id' to the 'empleado' table.
            // - unsignedBigInteger:  Specifies the data type as an unsigned big integer (for IDs).
            // - nullable():  Allows this column to contain NULL values.  This is important for cases where an employee might not be assigned to a department initially.

            $table->foreign('departamento_id')
                  ->references('id')
                  ->on('departamento');
            // Defines a foreign key constraint for the 'departamento_id' column.
            // - foreign('departamento_id'):  Specifies that 'departamento_id' in the 'empleado' table is the foreign key.
            // - references('id'):  Specifies that this foreign key references the 'id' column...
            // - on('departamento'):  ...in the 'departamento' table.
            // This establishes the relationship between empleados and departamentos.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
