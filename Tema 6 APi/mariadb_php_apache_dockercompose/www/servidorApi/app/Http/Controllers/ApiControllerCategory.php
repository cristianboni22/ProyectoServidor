<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ApiControllerCategory extends Controller
{
    public function index(Request $req) {
        if ($req->user()->tokenCan('server:read')) {
            $categorias=Category::with('books')->get();
            return response()->json(['respuesta'=>$categorias]);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401);
    }

    public function show(Request $req, $id) {
        if ($req->user()->tokenCan('server:read')) {
            $categoria=DB::table('categories')->select('name')->where('id','=',$id)->get();
            return response()->json(['respuesta'=>$categoria]);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401);
    }

    public function store(Request $req) {
        if ($req->user()->tokenCan('server:create')) {
            Category::create(['name'=>$req->name]);
            if ($req->has('books')) {
                $categoria->books()->sync($req->books);
            }
            return response()->json(['respuesta'=>'¡Categoría creada!']); 
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401);  
    }

    public function update(Request $req, string $id) {
        if ($req->user()->tokenCan('server:update')) {
            $categoria=Category::find($id);
            $categoria->name = $req->name;
            $categoria->save();
            if ($req->has('books')) {
                $categoria->books()->sync($req->books);
            }
            return response()->json(['respuesta'=>'¡Categoría actualizada!']);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401);
        
    }

    public function destroy(Request $req, string $id) {
        if ($req->user()->tokenCan('server:destroy')) {
            $categoria=Category::destroy($id);
            if ($req->has('books')) {
                $categoria->books()->detach();
            }     
            return response()->json(['respuesta'=>'¡Categoría borrada!']);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401);
    }
}
