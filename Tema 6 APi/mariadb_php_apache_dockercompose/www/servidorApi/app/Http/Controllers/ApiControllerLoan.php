<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

class ApiControllerLoan extends Controller
{
    public function index(Request $req) {
        if ($req->user()->tokenCan('server:read')) {
            $prestamos=Loan::orderBy('id')->get();
            return response()->json(['respuesta'=>$prestamos]);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401);
    }

    public function show(Request $req, $id) {
        if ($req->user()->tokenCan('server:read')) {
            $prestamo=DB::table('loans')->select('loan_date','return_date','book_id')->where('id','=',$id)->get();
            return response()->json(['respuesta'=>$prestamo]);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401); 
    }

    public function store(Request $req) {
        if ($req->user()->tokenCan('server:create')) {
            Loan::create(['loan_date'=>$req->loan_date,'return_date'=>$req->return_date,"book_id"=>$req->book_id]);
            return response()->json(['respuesta'=>'¡Prestamo creado!']);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401); 
    }

    public function update(Request $req, string $id) {
        if ($req->user()->tokenCan('server:update')) {
            $prestamo=Loan::find($id);
            $prestamo->loan_date = $req->loan_date;
            $prestamo->return_date = $req->return_date;
            $prestamo->book_id = $req->book_id;
            $prestamo->save();
            return response()->json(['respuesta'=>'¡Prestamo actualizado!']);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401);
    }

    public function destroy(Request $req, string $id) {
        if ($req->user()->tokenCan('server:destroy')) {
            $prestamo=Loan::destroy($id);
            return response()->json(['respuesta'=>'¡Prestamo borrado!']);
        } else
            return response()->json(['respuesta' => '¡No autorizado!'], 401);
    }
}
