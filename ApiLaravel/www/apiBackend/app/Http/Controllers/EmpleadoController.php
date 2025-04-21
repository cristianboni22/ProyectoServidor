<?php

namespace App\Http\Controllers;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
{
    // Obtener todos los empleados
    public function index()
    {
        $user = Auth::user();

        if ($user->login !== 'SuperAdmin') {
            return response()->json(['error' => 'Falta de permisos. Solo SuperAdmin puede acceder.'], 403);
        }
        $empleados = Empleado::all();
        if ($empleados->isEmpty()) {
            return response()->json(['message' => 'No existen empleados'], 404);
        }
        return response()->json($empleados, 200);
    }

    // Obtener un empleado por DNI
    public function show($dni)
    {
        $empleado = Empleado::find($dni);
        if (!$empleado) {
            return response()->json(['message' => 'No existe empleado'], 404);
        }
        return response()->json($empleado, 200);
    }

    // Crear un nuevo empleado
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->login !== 'SuperAdmin') {
            return response()->json(['error' => 'Falta de permisos. Solo SuperAdmin puede acceder.'], 403);
        }
        $request->validate([
            'dni' => 'required|unique:empleado,dni',
            'nombre_completo' => 'required',
            'login' => 'required|unique:empleado,login',
            'password' => 'required',
            'departamento_id' => 'required|exists:departamento,id',
        ]);
        $datosEmpleado = $request->all();
        $datosEmpleado['password'] = Hash::make($request->password);

        $empleado = Empleado::create($datosEmpleado);
        return response()->json(['message' => 'Empleado creado correctamente'], 201);
    }

    // Modificar un empleado
    public function update(Request $request, $dni)
    {
        $user = Auth::user();

        if ($user->login !== 'SuperAdmin') {
            return response()->json(['error' => 'Falta de permisos. Solo SuperAdmin puede acceder.'], 403);
        }
        $empleado = Empleado::find($dni);
        if (!$empleado) {
            return response()->json(['message' => 'No existe empleado'], 404);
        }
        $request->validate([
            'nombre_completo' => 'required',
            'login' => 'required',
            'password' => 'required',
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

        if ($user->login !== 'SuperAdmin') {
            return response()->json(['error' => 'Falta de permisos. Solo SuperAdmin puede acceder.'], 403);
        }
        $empleado = Empleado::find($dni);
        if (!$empleado) {
            return response()->json(['message' => 'No existe empleado'], 404);
        }

        $empleado->delete();
        return response()->json(['message' => 'Empleado eliminado correctamente'], 200);
    }
}
