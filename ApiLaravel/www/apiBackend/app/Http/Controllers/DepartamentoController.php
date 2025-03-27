<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Departamento;

class DepartamentoController extends Controller
{
    //
    //public function index()
    //{
    //    $departamentos = Departamento::with('empleados')->get();
    //    return response()->json(['respuesta' => $departamentos]);
    //}
        // Obtener todos los departamentos
        public function index(){
            $departamentos = Departamento::all();
            if ($departamentos->isEmpty()) {
                return response()->json(['message' => 'No existe ningún departamento'], 404);
            }
            return response()->json($departamentos, 200);
        }
    
        // Obtener un departamento por id
        public function show($id){
            $departamento = Departamento::find($id);
            if (!$departamento) {
                return response()->json(['message' => 'No existe departamento'], 404);
            }
            return response()->json($departamento, 200);
        }
    
        // Crear un nuevo departamento
        public function store(Request $request){
            $request->validate([
                'nombre' => 'required|unique:departamento,nombre',
                'telefono' => 'required',
                'email' => 'required|email',
            ]);
    
            $departamento = Departamento::create($request->all());
            return response()->json(['message' => 'Departamento creado correctamente'], 201);
        }
    
        // Modificar un departamento
        public function update(Request $request, $id){
            $departamento = Departamento::find($id);
            if (!$departamento) {
                return response()->json(['message' => 'No existe departamento'], 404);
            }
            $request->validate([
                'nombre' => 'required',
                'telefono' => 'required',
                'email' => 'required|email',
            ]);
    
            $departamento->update($request->all());
            return response()->json(['message' => 'Departamento modificado correctamente'], 200);
        }
    
        // Eliminar un departamento
        public function destroy($id){
            $departamento = Departamento::find($id);
            if (!$departamento) {
                return response()->json(['message' => 'No existe departamento'], 404);
            }
    
            $departamento->delete();
            return response()->json(['message' => 'Departamento eliminado correctamente'], 200);
        }
    }
