<?php

namespace App\Http\Controllers;

use App\Models\agregmiado;
use Illuminate\Http\Request;

class AgregmiadoController extends Controller
{

    public function newAgremiado(Request $req){
        $agremiado = agregmiado::create($req->all());
        return response()->json($agremiado,200);
    }

    public function getAgremiados(){
        $agremiados = agregmiado::all();
        return response()->json($agremiados,200);
    }

    public function getAgremiadoById($id){
        $agremiado = agregmiado::find($id);
        return response()->json($agremiado,200);
    }

    public function updateAgremiado(Request $req, $id){
        $agremiado = agregmiado::find($id);
        $agremiado->update($req->all());
        return response()->json($agremiado,200);
    }

    public function deleteAgremiado($id){
        $agremiado = agregmiado::find($id);
        $agremiado->delete();
        return response()->json(['message'=>'Agremiado deletado com sucesso!'],200);
    }

}
