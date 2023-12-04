<?php

namespace App\Http\Controllers;

use App\Models\solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SolicitudController extends Controller
{

    public function storeSolicitud(Request $request)
    {
        try{
            $request->validate([
                'route_file' => 'required|mimes:docx,pdf,jpg',
                'id_agregmiado' => 'required'
            ]);

            if ($request->hasFile('route_file')) {
                $file = $request->file('route_file');
                $path = Storage::path($file->store('archivos'));
                // $file->store('archivos'); // 'archivos' es la carpeta donde se guardarán los archivos

                $archivo = new solicitud;
                $archivo->route_file = $path;
                $archivo->id_agregmiado = $request->id_agregmiado;
                $archivo->save();

                return response()->json([
                    'message' => 'Archivo subido correctamente',
                    'archivo' => $archivo

                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al subir el archivo',
                'error' => $e->getMessage()
            ], 422);
        }
        // $request->validate([
        //     'route_file' => 'required|mimes:docx,pdf,jpg',
        //     'id_agregmiado' => 'required'
        // ]);

        // if ($request->hasFile('route_file')) {
        //     $file = $request->file('route_file');
        //     $path = $file->store('archivos'); // 'archivos' es la carpeta donde se guardarán los archivos
        //     // También puedes guardar la ruta en la base de datos si es necesario
        //     // Ejemplo: $solicitud->route_file = $path;
        //     // $solicitud->save();


        //     return response()->json([
        //         'message' => 'Archivo subido correctamente'

        //     ]);
        // }

        // return response()->json(['message' => 'Error al subir el archivo'], 422);
    }

    public function getSolicitud()
    {
        try {
            // $solicitudes = Solicitud::with('agregmiado')->get();
            $solicitudes = Solicitud::all();

            if(is_null($solicitudes) || count($solicitudes) == 0){
                return response()->json([
                    'message' => 'No se encontraron solicitudes'
                ], 404);
            }

            foreach ($solicitudes as $solicitud) {
                $solicitud->route_file = Storage::url($solicitud->route_file);
            }

            return response()->json($solicitudes);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las solicitudes',
                'error' => $e->getMessage()
            ], 500);
        }
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
