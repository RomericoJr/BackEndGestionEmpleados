<?php

namespace App\Http\Controllers;

use App\Models\solicitud;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    public function newSolicitud(Request $request)
    {
        $solicitud = solicitud::create($request->all());
        return response()->json($solicitud, 201);
    }

    public function getSolicitud(Request $request)
    {
        $solicitud = solicitud::where('id_agregmiado', $request->id_agregmiado)->get();
        return response()->json($solicitud, 200);
    }

    public function getSolicitudById(Request $request)
    {
        $solicitud = solicitud::where('id', $request->id)->get();
        return response()->json($solicitud, 200);
    }

    // public function updateSolicitud(Request $request)
    // {
    //     $solicitud = solicitud::where('id', $request->id)->update($request->all());
    //     return response()->json($solicitud, 200);
    // }

    public function deleteSolicitud(Request $request)
    {
        $solicitud = solicitud::where('id', $request->id)->delete();
        return response()->json(['message' => 'Solicitud eliminada correctamente!'], 200);
    }
}
