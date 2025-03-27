<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
class ApiController extends Controller
{
    public function index(Request $req) {
        if ($req->user()->tokenCan('server:read')) {
            $libros=Book::with('author', 'categories')->get();
            return response()->json(['respuesta'=>$libros]);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401);
    }

    public function show(Request $req, $id) {
        if ($req->user()->tokenCan('server:read')) {
            $libro=DB::table('books')->select('title','published_year','author_id')->where('id','=',$id)->get();
            return response()->json(['respuesta'=>$libro]);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401); 
    }

    public function store(Request $req) {
        if ($req->user()->tokenCan('server:create')) {
            Book::create(['title'=>$req->title,'published_year'=>$req->published_year,"author_id"=>$req->author_id]);
            if ($req->has('categories')) {
                $libro->categories()->sync($req->categories);
            }
            return response()->json(['respuesta'=>'¡Libro creado!']);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401);    
    }

    public function update(Request $req, string $id) {
        if ($req->user()->tokenCan('server:update')) {
            $libro=Book::find($id);
            $libro->title = $req->title;
            $libro->published_year = $req->published_year;
            $libro->author_id = $req->author_id;
            $libro->save();
            if ($req->has('categories')) {
                $libro->categories()->sync($req->categories);
            }
            return response()->json(['respuesta'=>'¡Libro actualizado!']);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401);     
    }

    public function destroy(Request $req, string $id) {
        if ($req->user()->tokenCan('server:destroy')) {
            $libro=Book::destroy($id);
            if ($req->has('categories')) {
                $libro->categories()->detach();
            }
            return response()->json(['respuesta'=>'¡Libro borrado!']);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401);
    }
}
