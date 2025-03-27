<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamento'; // Asegura que la tabla sea la correcta

    public function empleados() // En plural porque un departamento tiene muchos empleados
    {
        return $this->hasMany(Empleado::class, 'departamento_id'); 
    }
}
