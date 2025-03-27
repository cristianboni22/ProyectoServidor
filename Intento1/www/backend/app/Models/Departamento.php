<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    // Definir la tabla, si no sigue la convención de nombres pluralizados
    protected $table = 'departamento';

    // Indicar las columnas que pueden ser llenadas (mass assignable)
    protected $fillable = [
        'nombre',
        'telefono',
        'email',
    ];

    /**
     * Relación: Un departamento puede tener muchos empleados.
     */
    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'id_departamento');
    }
}
