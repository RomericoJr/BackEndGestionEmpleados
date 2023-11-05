<?php

namespace App\Http\Controllers;

use App\Models\solicitud;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{

    public function storeSolicitud(Request $request)
    {
        $request->validate([
            'route_file' => 'required|mimes:docx,pdf,jpg',
            'id_agregmiado' => 'required'
        ]);

        if ($request->hasFile('route_file')) {
            $file = $request->file('route_file');
            $path = $file->store('archivos'); // 'archivos' es la carpeta donde se guardarán los archivos
            // También puedes guardar la ruta en la base de datos si es necesario
            // Ejemplo: $solicitud->route_file = $path;
            // $solicitud->save();
            return response()->json(['message' => 'Archivo subido con éxito']);
        }

        return response()->json(['message' => 'Error al subir el archivo'], 422);
    }

    public function getSolicitud(){
        $solicitud = solicitud::all();
        return response()->json($solicitud);
    }

    public function getSolicitudById($id){
        $solicitud = solicitud::find($id);
        return response()->json($solicitud);
    }


    public function deleteSolicitud($id){
        $solicitud = solicitud::find($id);
        $solicitud->delete();
        return response()->json($solicitud);
    }

}
