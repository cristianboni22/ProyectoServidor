<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Empleado extends Authenticatable implements JWTSubject

{
    use HasApiTokens, HasFactory;

    protected $table = 'empleado';
    protected $primaryKey = 'dni';
    public $incrementing = false; 
    
    protected $fillable = [
        'login',
        'password',
        'dni',
        'nombre_completo',
        'departamento_id'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

// Métodos JWT
public function getJWTIdentifier()
{
    return $this->getKey();
}

public function getJWTCustomClaims()
{
    return [];
}

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }
}
