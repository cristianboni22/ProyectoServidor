<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $empleado = Empleado::create([
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'dni' => $request->dni,
            'nombre_completo' => $request->nombre_completo,
            'departamento_id' => $request->departamento_id,
        ]);

        if (!$empleado) {
            return response()->json(['error' => 'Problema con el Usuario'], 404);
        }

        return response()->json(['respuesta' => 'El usuario ha sido creado con exito en la BD']);
    }

    public function login(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string'
        ]);

        // Buscar al empleado por su login
        $empleado = Empleado::where('login', $request->login)->first();

        // Si no se encuentra el empleado
        if (!$empleado) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Verificar si la contraseña es correcta
        if (!Hash::check($request->password, $empleado->password)) {
            return response()->json(['error' => 'Contraseña incorrecta'], 401);
        }

        // Si todo está bien, devolver un mensaje de éxito
        return response()->json(['message' => 'Login exitoso']);
    }
}
