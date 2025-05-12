<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; // Importa la clase Request para manejar las peticiones HTTP.
use App\Models\Departamento; // Importa el modelo Departamento, que representa la tabla de departamentos en la base de datos.
use Illuminate\Support\Facades\Auth; // Importa la clase Auth para manejar la autenticación de usuarios.

class DepartamentoController extends Controller
{
    // Obtener todos los departamentos
    public function index()
    {
        $user = Auth::user(); // Obtiene el usuario autenticado actualmente.  Aunque no se usa en esta función, se incluye.

        $departamentos = Departamento::all(); // Obtiene todos los registros de la tabla departamentos.
        if ($departamentos->isEmpty()) {
            // Si no hay departamentos, devuelve un mensaje de error con código 404 (No encontrado).
            return response()->json(['message' => 'No existe ningún departamento'], 404);
        }
        // Si hay departamentos, los devuelve en formato JSON con código 200 (OK).
        return response()->json($departamentos, 200);
    }


    // Obtener un departamento por id
    public function show($id)
    {
        $user = Auth::user(); // Obtiene el usuario autenticado.

        //Solo puede SuperAdmin sino salta este errror
        if ($user->login !== 'SuperAdmin') {
            // Si el usuario no es SuperAdmin, devuelve un error 403 (No autorizado).
            return response()->json(['error' => 'Falta de permisos. Solo SuperAdmin puede acceder.'], 403);
        }
        $departamento = Departamento::find($id); // Busca un departamento por su ID.
        if (!$departamento) {
            // Si no se encuentra el departamento, devuelve un error 404.
            return response()->json(['message' => 'No existe departamento'], 404);
        }
        // Si se encuentra, devuelve el departamento en formato JSON con código 200.
        return response()->json($departamento, 200);
    }

    // Crear un nuevo departamento
    public function store(Request $request)
    {
        $user = Auth::user(); // Obtiene el usuario autenticado.

        //Solo puede SuperAdmin sino salta este errror
        if ($user->login !== 'SuperAdmin') {
            // Solo los SuperAdmin pueden crear departamentos.
            return response()->json(['error' => 'Falta de permisos. Solo SuperAdmin puede acceder.'], 403);
        }
        $request->validate([
            // Valida los datos de la petición.  'nombre' debe ser único.
            'nombre' => 'required|unique:departamento,nombre',
            'telefono' => 'required',
            'email' => 'required|email',
        ]);

        $departamento = Departamento::create($request->all()); // Crea un nuevo departamento con los datos de la petición.
        return response()->json(['message' => 'Departamento creado correctamente'], 201); // Devuelve un mensaje de éxito con código 201 (Creado).
    }

    // Modificar un departamento
    public function update(Request $request, $id)
    {
        $user = Auth::user(); // Obtiene el usuario autenticado.

        //Solo puede SuperAdmin sino salta este errror
        if ($user->login !== 'SuperAdmin') {
            // Solo SuperAdmin puede modificar departamentos.
            return response()->json(['error' => 'Falta de permisos. Solo SuperAdmin puede acceder.'], 403);
        }
        $departamento = Departamento::find($id); // Busca el departamento a modificar.
        if (!$departamento) {
            // Si no existe, devuelve un error 404.
            return response()->json(['message' => 'No existe departamento'], 404);
        }

        $request->validate([
            // Valida los datos de la petición.
            'nombre' => 'required', // A diferencia del store, aquí no se requiere que sea único, pero sí que esté presente.
            'telefono' => 'required',
            'email' => 'required|email',
        ]);

        $departamento->update($request->all()); // Actualiza el departamento con los nuevos datos.
        return response()->json(['message' => 'Departamento modificado correctamente'], 200); // Devuelve un mensaje de éxito.
    }

    // Eliminar un departamento
    public function destroy($id)
    {
        $user = Auth::user(); // Obtiene el usuario autenticado.

        //Solo puede SuperAdmin sino salta este errror
        if ($user->login !== 'SuperAdmin') {
            // Solo SuperAdmin puede eliminar departamentos.
            return response()->json(['error' => 'Falta de permisos. Solo SuperAdmin puede acceder.'], 403);
        }
        $departamento = Departamento::find($id); // Busca el departamento a eliminar.
        if (!$departamento) {
            // Si no existe, devuelve un error 404.
            return response()->json(['message' => 'No existe departamento'], 404);
        }

        $departamento->delete(); // Elimina el departamento.
        return response()->json(['message' => 'Departamento eliminado correctamente'], 200); // Devuelve un mensaje de éxito.
    }
}

