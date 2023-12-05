<?php

namespace App\Http\Controllers;

use App\Models\solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
class SolicitudController extends Controller
{

    public function storeSolicitud(Request $request)
    {
        try {
            $request->validate([
                'route_file' => 'required|mimes:docx,pdf,jpg',
                'id_agregmiado' => 'required'
            ]);

            if ($request->hasFile('route_file')) {
                $file = $request->file('route_file');

                // Almacenar la imagen en la carpeta 'public/archivos' con un nombre único
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = Storage::putFileAs('public/archivos', $file, $fileName);

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
    }

    public function getSolicitud()
    {
        try {
            $solicitudes = Solicitud::all();

            if ($solicitudes->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron solicitudes'
                ], 404);
            }

            // Recorrer las solicitudes y realizar las modificaciones necesarias
            foreach ($solicitudes as $solicitud) {
                $solicitud->route_file = asset(Storage::url($solicitud->route_file));
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


    public function descargarSolicitud($id)
    {
        try {
            $solicitud = solicitud::find($id);

            if (is_null($solicitud)) {
                return response()->json([
                    'message' => 'No se encontró la solicitud'
                ], 404);
            }

            $fileName = 'solicitud_' . $id . '.' . pathinfo($solicitud->route_file, PATHINFO_EXTENSION);

            // Obtén la URL completa del archivo de almacenamiento
            $url = URL::to('/public/archivos/' . $fileName);

            return response()->json([
                'message' => 'Enlace de descarga generado correctamente',
                'download_link' => $url
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar el enlace de descarga',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
