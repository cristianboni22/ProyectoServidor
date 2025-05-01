<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|unique:empleado,login',
            'password' => 'required|string',
            'dni' => 'required|string|unique:empleado,dni',
            'nombre_completo' => 'required|string',
            'departamento_id' => 'required|exists:departamento,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }


    // Verificar si el login es "superadmin" (ignorar mayúsculas/minúsculas)
    if (($request->login) === 'superadmin'&&'SuperAdmin') {
        return response()->json(['error' => ['login' => 'No se puede usar "SuperAdmin" como login.']], 400);
    }
    if (($request->nombre_completo) === 'superadmin'&&'SuperAdmin') {
        return response()->json(['error' => ['login' => 'No se puede usar "SuperAdmin" como nombre completo.']], 400);
    }
    if (($request->dni) === 'superadmin'&&'SuperAdmin') {
        return response()->json(['error' => ['login' => 'No se puede usar "SuperAdmin" como nombre completo.']], 400);
    }


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
        // Aquí decides si el login pertenece a un SuperAdmin
        $role = ($empleado->login === 'SuperAdmin') ? 'SuperAdmin' : 'Empleado';

        // Crear el token de autenticación
        $token = $empleado->createToken('token-name');

        // Responder con el token y el rol
        return response()->json([
            'mensaje' => $token->plainTextToken,
            'role' => $role 
        ]);
    }
}
