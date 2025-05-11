<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
{

    // Obtener un empleado por DNI
    public function show($dni)
    {
        $empleado = Empleado::find($dni);
        if (!$empleado) {
            return response()->json(['message' => 'No existe empleado'], 404);
        }
        return response()->json($empleado, 200);
    }










    // Obtener todos los empleados
    public function index()
    {
        $user = Auth::user();

        //Solo puede SuperAdmin sino salta este errror
        if ($user->login !== 'SuperAdmin') {
            return response()->json(['error' => 'Falta de permisos. Solo SuperAdmin puede acceder.'], 403);
        }

        $empleados = Empleado::all();
        if ($empleados->isEmpty()) {
            return response()->json(['message' => 'No existen empleados'], 404);
        }
        return response()->json($empleados, 200);
    }



    // Crear un nuevo empleado
    public function store(Request $request)
    {
        $user = Auth::user();

        //Solo puede SuperAdmin sino salta este errror
        if (strcasecmp(trim($request->login), 'superadmin') === 0) {
            return response()->json(['error' => ['login' => 'No se puede usar "SuperAdmin" como login.']], 400);
        }
        if (strcasecmp(trim($request->nombre_completo), 'superadmin') === 0) {
            return response()->json(['error' => ['nombre_completo' => 'No se puede usar "SuperAdmin" como nombre completo.']], 400);
        }
        if (strcasecmp(trim($request->dni), 'superadmin') === 0) {
            return response()->json(['error' => ['dni' => 'No se puede usar "SuperAdmin" como DNI.']], 400);
        }

        $request->validate([
            'dni' => [
                'required',
                'unique:empleado,dni',
                'regex:/^\d{7,9}[A-Z]$/i' // ejemplo: 12345678A
            ],
            'nombre_completo' => 'required|string|max:100',
            'login' => [
                'required',
                'unique:empleado,login',
                'regex:/^[a-zA-Z0-9_]{4,20}$/'   //ejemplo:Cristian , cristian
            ],
            'password' => [
                'required',
                'min:8'             //ejemplo:password
            ],
            'departamento_id' => 'required|exists:departamento,id',
        ]);

        if (strtolower($request->login) === 'superadmin') {
            return response()->json(['error' => ['login' => 'No se puede usar "SuperAdmin" como login.']], 400);
        }
        if (strtolower($request->nombre_completo) === 'superadmin') {
            return response()->json(['error' => ['nombre_completo' => 'No se puede usar "SuperAdmin" como nombre completo.']], 400);
        }
        if (strtolower($request->dni) === 'superadmin') {
            return response()->json(['error' => ['dni' => 'No se puede usar "SuperAdmin" como DNI.']], 400);
        }

        $datosEmpleado = $request->all();
        $datosEmpleado['password'] = Hash::make($request->password);

        $empleado = Empleado::create($datosEmpleado);
        return response()->json(['message' => 'Empleado creado correctamente'], 201);
    }

    // Modificar un empleado
    public function update(Request $request, $dni)
    {
        $user = Auth::user();

        if (strtolower($request->login) === 'superadmin') {
            return response()->json(['error' => ['login' => 'No se puede usar "SuperAdmin" como login.']], 400);
        }
        if (strtolower($request->nombre_completo) === 'superadmin') {
            return response()->json(['error' => ['nombre_completo' => 'No se puede usar "SuperAdmin" como nombre completo.']], 400);
        }
        if (strtolower($request->dni) === 'superadmin') {
            return response()->json(['error' => ['dni' => 'No se puede usar "SuperAdmin" como DNI.']], 400);
        }

        $empleado = Empleado::find($dni);
        if (!$empleado) {
            return response()->json(['message' => 'No existe empleado'], 404);
        }
        $request->validate([
            'dni' => [
                'required',
                'unique:empleado,dni',
                'regex:/^\d{7,9}[A-Z]$/i' // ejemplo: 12345678A
            ],
            'nombre_completo' => 'required|string|max:100',
            'login' => [
                'required',
                'unique:empleado,login',
                'regex:/^[a-zA-Z0-9_]{4,20}$/'   //ejemplo:Cristian , cristian
            ],
            'password' => [
                'required',
                'min:8'             //ejemplo:password
            ],
            'departamento_id' => 'required|exists:departamento,id',
        ]);
        $datosEmpleado = $request->all();
        $datosEmpleado['password'] = Hash::make($request->password);

        $empleado->update($datosEmpleado);
        return response()->json(['message' => 'Empleado modificado correctamente'], 200);
    }

    // Eliminar un empleado
    public function destroy($dni)
    {
        $user = Auth::user();

        //Solo puede SuperAdmin sino salta este errror
        if ($user->login !== 'SuperAdmin') {
            return response()->json(['error' => 'Falta de permisos. Solo SuperAdmin puede acceder.'], 403);
        }
        
        if (strtolower($dni) === 'superadmin') {
            return response()->json(['error' => 'No se puede eliminar al SuperAdmin.'], 400);
        }
        $empleado = Empleado::find($dni);
        if (!$empleado) {
            return response()->json(['message' => 'No existe empleado'], 404);
        }

        $empleado->delete();
        return response()->json(['message' => 'Empleado eliminado correctamente'], 200);
    }
}
