<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleado'; // Asegura que la tabla sea la correcta

    public function departamento() // En singular porque un empleado tiene UN departamento
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }
}
