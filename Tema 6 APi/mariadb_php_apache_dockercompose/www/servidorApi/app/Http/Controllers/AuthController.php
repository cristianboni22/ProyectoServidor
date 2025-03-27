<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function register(Request $req) {
        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password)
        ]);
        
        return response()->json(['respuesta'=>'El usuario ha sido creado con exito en la BD']);
    }

    public function login(Request $req) {
        $credentials = $req->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('miToken', ['server:read','server:create'])->plainTextToken;
            return response()->json(['respuesta' => $token]);
        }

        return response()->json(['respuesta' => 'Unauthorized'], 401);
    }

    public function logout() {
        auth()->user()->tokens()->delete();
        return response()->json(['respuesta' => 'Tokens revoked']);
    }
}
