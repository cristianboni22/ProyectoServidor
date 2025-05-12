<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Importa el trait HasFactory.
use Illuminate\Foundation\Auth\User as Authenticatable; // Importa la clase User de Laravel para la autenticación.
use Laravel\Sanctum\HasApiTokens; // Importa el trait HasApiTokens para la autenticación con Sanctum (API tokens).
use Tymon\JWTAuth\Contracts\JWTSubject; // Importa la interfaz JWTSubject para la autenticación con JWT.

class Empleado extends Authenticatable implements JWTSubject // La clase Empleado extiende Authenticatable y implementa JWTSubject.
{
    use HasApiTokens, HasFactory; // Utiliza los traits HasApiTokens y HasFactory.

    protected $table = 'empleado'; // Especifica el nombre de la tabla en la base de datos.
    protected $primaryKey = 'dni'; // Especifica que la clave primaria de la tabla es la columna 'dni'.
    public $incrementing = false; // Indica que la clave primaria no es autoincremental.  Esto es necesario porque el DNI no es un número que se incrementa automáticamente.

    protected $fillable = [
        // Define los atributos que se pueden asignar masivamente.
        'login',
        'password',
        'dni',
        'nombre_completo',
        'departamento_id'
    ];
    protected $hidden = [
        // Define los atributos que no se deben incluir en las respuestas JSON por defecto.
        'password',
        'remember_token',
    ];

    // Métodos JWT
    public function getJWTIdentifier()
    {
        // Implementación del método de la interfaz JWTSubject.  Devuelve el identificador único del usuario (en este caso, el DNI).
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        // Implementación del método de la interfaz JWTSubject.  Devuelve un array con reclamaciones personalizadas para el token JWT.  En este caso, está vacío.
        return [];
    }

    public function departamento()
    {
        // Define la relación "pertenece a" con el modelo Departamento.
        // Un empleado pertenece a un departamento.
        return $this->belongsTo(Departamento::class, 'departamento_id'); // El segundo argumento es la clave foránea en esta tabla ('empleado') que referencia a la tabla 'departamento'.
    }
}
