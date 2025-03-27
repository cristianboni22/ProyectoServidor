<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use Illuminate\Support\Facades\DB;
class ApiControllerAuthor extends Controller
{
    public function index(Request $req) {
        if ($req->user()->tokenCan('server:read')) {
            $autores=Author::orderBy('id')->get();
            return response()->json(['respuesta'=>$autores]);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401); 
    }

    public function show(Request $req, $id) {
        if ($req->user()->tokenCan('server:read')) {
            $autor=DB::table('authors')->select('name','country')->where('id','=',$id)->get();
            return response()->json(['respuesta'=>$autor]);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401); 
    }

    public function store(Request $req) {
        if ($req->user()->tokenCan('server:create')) {
            Author::create(['name'=>$req->name,'country'=>$req->country]);
            return response()->json(['respuesta'=>'¡Autor creado!']);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401); 
    }

    public function update(Request $req, string $id) {
        if ($req->user()->tokenCan('server:update')) {
            $autor=Author::find($id);
            $autor->name = $req->name;
            $autor->country = $req->country;
            $autor->save();
            return response()->json(['respuesta'=>'¡Autor actualizado!']);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401);
    }

    public function destroy(Request $req, string $id) {
        if ($req->user()->tokenCan('server:destroy')) {
            $autor=Author::destroy($id);
            return response()->json(['respuesta'=>'¡Autor borrado!']);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401);
    }
}
