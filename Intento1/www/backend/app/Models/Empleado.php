<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Empleado extends Authenticatable 
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'empleado';

    protected $fillable = [
        'dni',
        'nombre_completo',
        'login',
        'password',
        'id_departamento',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }
}

