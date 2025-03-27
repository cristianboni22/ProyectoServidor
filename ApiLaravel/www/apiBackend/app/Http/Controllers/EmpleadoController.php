<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    // Obtener todos los empleados
    public function index(){
        $empleados = Empleado::all();
        if ($empleados->isEmpty()) {
            return response()->json(['message' => 'No existen empleados'], 404);
        }
        return response()->json($empleados, 200);
    }

    // Obtener un empleado por DNI
    public function show($dni){
        $empleado = Empleado::find($dni);
        if (!$empleado) {
            return response()->json(['message' => 'No existe empleado'], 404);
        }
        return response()->json($empleado, 200);
    }

    // Crear un nuevo empleado
    public function store(Request $request){
        $request->validate([
            'dni' => 'required|unique:empleado,dni',
            'nombre_completo' => 'required',
            'login' => 'required|unique:empleado,login',
            'password' => 'required',
            'departamento_id' => 'required|exists:departamento,id',
        ]);
        $empleado = Empleado::create($request->all());
        return response()->json(['message' => 'Empleado creado correctamente'], 201);
    }

    // Modificar un empleado
    public function update(Request $request, $dni){
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
        $empleado->update($request->all());
        return response()->json(['message' => 'Empleado modificado correctamente'], 200);
    }

    // Eliminar un empleado
    public function destroy($dni){
        $empleado = Empleado::find($dni);
        if (!$empleado) {
            return response()->json(['message' => 'No existe empleado'], 404);
        }

        $empleado->delete();
        return response()->json(['message' => 'Empleado eliminado correctamente'], 200);
    }
}
