<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Importa el trait HasFactory para poder usar Factories en las pruebas.
use Illuminate\Database\Eloquent\Model; // Importa la clase Model, la clase base para todos los modelos de Eloquent.

class Departamento extends Model
{
    use HasFactory; // Utiliza el trait HasFactory.  Esto permite usar Departamento::factory() para crear instancias de Departamento en tests.

    protected $table = 'departamento';  // Especifica el nombre de la tabla en la base de datos.  Si no se define, Laravel asume que es 'departamentos' (plural del nombre de la clase).

    protected $fillable = [
        'nombre',
        'email',
        'telefono'
    ];  // Define los atributos que se pueden asignar masivamente (mass assignment).  Esto protege contra asignaciones inesperadas de datos.

    public function empleados()
    {
        // Define la relación "uno a muchos" con el modelo Empleado.
        // Un departamento puede tener muchos empleados.
        return $this->hasMany(Empleado::class, 'departamento_id');  // El segundo argumento es la clave foránea en la tabla 'empleado' que referencia a la tabla 'departamento'.
    }
}
