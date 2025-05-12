<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;  // Esta línea está comentada, lo que significa que la verificación de correo electrónico está desactivada.
use Illuminate\Database\Eloquent\Factories\HasFactory; // Importa el trait HasFactory, que permite usar factories para crear instancias de User en las pruebas.
use Illuminate\Foundation\Auth\User as Authenticatable; // Importa la clase Authenticatable, que proporciona la funcionalidad de autenticación de usuarios.
use Illuminate\Notifications\Notifiable; // Importa el trait Notifiable, que permite enviar notificaciones (por ejemplo, por correo electrónico, SMS, etc.).

class User extends Authenticatable // La clase User extiende Authenticatable, lo que significa que hereda la funcionalidad de autenticación.
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable; // Utiliza los traits HasFactory y Notifiable.

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        // Define los atributos que se pueden asignar masivamente (mass assignment).  Esto protege contra la asignación inesperada de datos a través de peticiones HTTP.
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        // Define los atributos que no se deben incluir en las representaciones JSON o de array del modelo.  Esto es útil para ocultar datos sensibles, como las contraseñas.
        'password',
        'remember_token', // El token "remember me" para mantener la sesión del usuario.
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        // Define cómo se deben convertir ciertos atributos cuando se acceden.  Por ejemplo, convierte 'email_verified_at' a un objeto DateTime y cifra la 'password'.
        return [
            'email_verified_at' => 'datetime', // Indica que el atributo 'email_verified_at' debe ser convertido a un objeto de tipo datetime.
            'password' => 'hashed', // Indica que el atributo 'password' debe ser cifrado usando la función de hash por defecto de Laravel.
        ];
    }
}