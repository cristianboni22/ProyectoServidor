<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // REGISTRO DE EMPLEADO
    public function register(Request $request)
    {
        // Crear el usuario (empleado)
        $empleado = Empleado::create([
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'dni' => $request->dni,
            'nombre_completo' => $request->nombre_completo,
            'id_departamento' => $request->id_departamento, // Opcional si lo usas
        ]);

        return response()->json(['respuesta' => 'El usuario se ha creado'], 201);
    }

    // LOGIN DE EMPLEADO
    public function login(Request $req) {
        $credentials = $req->only('login', 'password');
    
        if (Auth::attempt($credentials)) {
            $empleado = Auth::user();
            $token = $empleado->createToken('miToken', ['server:read', 'server:create'])->plainTextToken;
            return response()->json(['respuesta' => $token]);
        }
    
        return response()->json(['respuesta' => 'Unauthorized'], 401);
    }
    

    // CERRAR SESIÓN (LOGOUT)
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete(); // Revocar todos los tokens del usuario autenticado
        return response()->json(['respuesta' => 'Tokens revocados'], 200);
    }
}
