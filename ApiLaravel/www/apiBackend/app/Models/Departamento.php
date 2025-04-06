<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamento'; 

    protected $fillable = [
        'nombre',
        'email',
        'telefono'
    ];

    public function empleados() 
    {
        return $this->hasMany(Empleado::class, 'departamento_id'); 
    }
}
