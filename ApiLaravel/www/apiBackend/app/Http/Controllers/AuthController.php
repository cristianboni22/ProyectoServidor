<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $empleado = Empleado::create([
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'dni' => $request->dni,
            'nombre_completo' => $request->nombre_completo,
        ]);

        return response()->json(['respuesta' => 'El usuario ha sido creado con exito en la BD']);
    }

    public function login(Request $req) {
        $credentials = $req->only('login', 'password');
    
        if (Auth::attempt($credentials)) {
            $empleado = Auth::user();
            $token = $empleado->createToken('token-name')->plainTextToken;
            return response()->json(['respuesta' => $token]);
        }
    
        return response()->json(['respuesta' => 'Unauthorized'], 401);
    }
}
