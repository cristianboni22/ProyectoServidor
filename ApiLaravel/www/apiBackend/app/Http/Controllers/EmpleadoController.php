<?php

namespace App\Http\Controllers;

use App\Models\Empleado; // Importa el modelo Empleado.
use Illuminate\Http\Request; // Importa la clase Request para manejar las peticiones HTTP.
use Illuminate\Support\Facades\Hash; // Importa la clase Hash para el cifrado de contraseñas.
use Illuminate\Support\Facades\Auth; // Importa la clase Auth para manejar la autenticación.

class EmpleadoController extends Controller
{
    // Obtener un empleado por DNI
    public function show($dni)
    {
        $empleado = Empleado::find($dni); // Busca un empleado por su DNI.  OJO: find por DNI asume que DNI es la PK, lo cual no es estándar.
        if (!$empleado) {
            // Si no se encuentra el empleado, devuelve un error 404.
            return response()->json(['message' => 'No existe empleado'], 404);
        }
        // Si se encuentra, devuelve el empleado en formato JSON con código 200.
        return response()->json($empleado, 200);
    }
    

    // Obtener todos los empleados
    public function index()
    {
        $user = Auth::user(); // Obtiene el usuario autenticado.

        //Solo puede SuperAdmin sino salta este errror
        if ($user->login !== 'SuperAdmin') {
            // Solo el SuperAdmin puede listar todos los empleados.
            return response()->json(['error' => 'Falta de permisos. Solo SuperAdmin puede acceder.'], 403);
        }

        $empleados = Empleado::all(); // Obtiene todos los empleados.
        if ($empleados->isEmpty()) {
            // Si no hay empleados, devuelve un error 404.
            return response()->json(['message' => 'No existen empleados'], 404);
        }
        // Si hay empleados, los devuelve en formato JSON con código 200.
        return response()->json($empleados, 200);
    }



    // Crear un nuevo empleado
    public function store(Request $request)
    {
        $user = Auth::user(); // Obtiene el usuario autenticado.  Aunque no se usa, está presente en el código original.
        
        //Solo puede SuperAdmin sino salta este errror
        if ($user->login !== 'SuperAdmin') {
            // Solo el SuperAdmin puede eliminar empleados.
            return response()->json(['error' => 'Falta de permisos. Solo SuperAdmin puede acceder.'], 403);
        }

        if (strcasecmp(trim($request->login), 'superadmin') === 0) {
            // No se permite usar "superadmin" como login.
            return response()->json(['error' => ['login' => 'No se puede usar "SuperAdmin" como login.']], 400);
        }
        if (strcasecmp(trim($request->nombre_completo), 'superadmin') === 0) {
            // No se permite usar "superadmin" como nombre completo.
            return response()->json(['error' => ['nombre_completo' => 'No se puede usar "SuperAdmin" como nombre completo.']], 400);
        }
        if (strcasecmp(trim($request->dni), 'superadmin') === 0) {
            // No se permite usar "superadmin" como DNI.
            return response()->json(['error' => ['dni' => 'No se puede usar "SuperAdmin" como DNI.']], 400);
        }

        $request->validate([
            // Valida los datos de la petición.
            'dni' => [
                'required',
                'unique:empleado,dni', // El DNI debe ser único.
                'regex:/^\d{7,9}[A-Z]$/i' // Formato del DNI.
            ],
            'nombre_completo' => 'required|string|max:100',
            'login' => [
                'required',
                'unique:empleado,login', // El login debe ser único.
                'regex:/^[a-zA-Z0-9_]{4,20}$/'
            ],
            'password' => [
                'required',
                'min:8'
            ],
            'departamento_id' => 'required|exists:departamento,id',
        ]);

        $datosEmpleado = $request->all(); // Obtiene todos los datos del empleado.
        $datosEmpleado['password'] = Hash::make($request->password); // Cifra la contraseña.

        $empleado = Empleado::create($datosEmpleado); // Crea el nuevo empleado.
        return response()->json(['message' => 'Empleado creado correctamente'], 201); // Devuelve un mensaje de éxito.
    }

    // Modificar un empleado
    public function update(Request $request, $dni)
    {
        $user = Auth::user(); // Obtiene el usuario autenticado. Aunque no se usa, se incluye en el código original.

        //Solo puede SuperAdmin sino salta este errror
        if ($user->login !== 'SuperAdmin') {
            // Solo el SuperAdmin puede eliminar empleados.
            return response()->json(['error' => 'Falta de permisos. Solo SuperAdmin puede acceder.'], 403);
        }
        
        if (strtolower($request->login) === 'superadmin') {
            // No se permite modificar el login a "superadmin".
            return response()->json(['error' => ['login' => 'No se puede usar "SuperAdmin" como login.']], 400);
        }
        if (strtolower($request->nombre_completo) === 'superadmin') {
            // No se permite modificar el nombre a "superadmin".
            return response()->json(['error' => ['nombre_completo' => 'No se puede usar "SuperAdmin" como nombre completo.']], 400);
        }
        if (strtolower($request->dni) === 'superadmin') {
            // No se permite modificar el DNI a "superadmin".
            return response()->json(['error' => ['dni' => 'No se puede usar "SuperAdmin" como DNI.']], 400);
        }

        $empleado = Empleado::find($dni); // Busca el empleado por DNI.
        if (!$empleado) {
            // Si no existe el empleado, devuelve un error 404.
            return response()->json(['message' => 'No existe empleado'], 404);
        }
        $request->validate([
            // Valida los datos de la petición.
            'nombre_completo' => 'required|string|max:100',
            'login' => [
                'required',
                'unique:empleado,login',
                'regex:/^[a-zA-Z0-9_]{4,20}$/'
            ],
            'password' => [
                'required',
                'min:8'
            ],
            'departamento_id' => 'required|exists:departamento,id',
        ]);
        $datosEmpleado = $request->all();  // Obtiene los datos del request
        $datosEmpleado['password'] = Hash::make($request->password); //Cifra la contraseña
        $empleado->update($datosEmpleado); // Actualiza el empleado.
        return response()->json(['message' => 'Empleado modificado correctamente'], 200); // Devuelve un mensaje de éxito.
    }

    // Eliminar un empleado
    public function destroy($dni)
    {
        $user = Auth::user(); // Obtiene el usuario autenticado.

        //Solo puede SuperAdmin sino salta este errror
        if ($user->login !== 'SuperAdmin') {
            // Solo el SuperAdmin puede eliminar empleados.
            return response()->json(['error' => 'Falta de permisos. Solo SuperAdmin puede acceder.'], 403);
        }
        
        if (strtolower($dni) === 'superadmin') {
            // No se permite eliminar al SuperAdmin.
            return response()->json(['error' => 'No se puede eliminar al SuperAdmin.'], 400);
        }
        $empleado = Empleado::find($dni); // Busca el empleado por DNI.
        if (!$empleado) {
            // Si no existe el empleado, devuelve un error 404.
            return response()->json(['message' => 'No existe empleado'], 404);
        }

        $empleado->delete(); // Elimina el empleado.
        return response()->json(['message' => 'Empleado eliminado correctamente'], 200); // Devuelve un mensaje de éxito.
    }
}

