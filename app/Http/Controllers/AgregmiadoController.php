<?php

namespace App\Http\Controllers;

use App\Models\agregmiado;
use App\Models\sex;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgregmiadoController extends Controller
{

    public function registerAgregmiado(Request $request){

        try{
            $validateUser = Validator::make($request->all(),[
                'nombre' => 'required|string|max:255',
                'apellido_p' => 'required|string|max:255',
                'apellido_m' => 'required|string|max:255',
                'id_sexo' => 'required|string|max:255',
                'NUP' => 'required|string|max:10|unique:agregmiados',
                'NUE' => 'required|string|max:10|unique:agregmiados',
                'RFC' => 'required|string|max:13|unique:agregmiados',
                'NSS' => 'required|string|max:50|unique:agregmiados',
                'fecha_nacimiento' => 'required|string|max:255',
                'telefono' => 'required|string|max:10|unique:agregmiados',
                'cuota' => 'required|',
                'id_rol' => 'required|'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'Error en la validacion de datos',
                    'errors' => $validateUser->errors()
                ], 422);
            }

            $newAgregmiado = agregmiado::create([
                'nombre' => $request->get('nombre'),
                'apellido_p' => $request->get('apellido_p'),
                'apellido_m' => $request->get('apellido_m'),
                'id_sexo' => $request->get('id_sexo'),
                'NUP' => $request->get('NUP'),
                'NUE' => $request->get('NUE'),
                'RFC' => $request->get('RFC'),
                'NSS' => $request->get('NSS'),
                'fecha_nacimiento' => $request->get('fecha_nacimiento'),
                'telefono' => $request->get('telefono'),
                'cuota' => $request->get('cuota'),
                'id_rol' => $request->get('id_rol')
            ]);

            $newUser = User::create([
                'name' => $request->nombre,
                'NUE'=> $request->NUE,
                'id_agregmiado' => $newAgregmiado->id,
                'password' => bcrypt($request->NUE),
                'id_rol' => $request->id_rol
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado correctamente',
                'data0' => $newUser,
                'data2' => $newAgregmiado
            ], 201);

        } catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function getAgregmiado(){
        try{

            $agregmiado = agregmiado::addSelect([
                'genere' => sex::select('genere')
                    ->whereColumn('id_sexo', 'id')
            ])->get();

            // $agregmiado = agregmiado::all();
            return response()->json([
                'success' => true,
                'message' => 'Lista de agregmiados',
                'data' => $agregmiado
            ], 200);
        } catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los agregmiados',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function getAgregmiadoByNUE($NUE){
        try{
            $agregmiado = agregmiado::where('NUE', $NUE)->first();
            return response()->json([
                'success' => true,
                'message' => 'Agregmiado encontrado',
                'data' => $agregmiado
            ], 200);
        } catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el agregmiado',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function updateAgregmiado(Request $request, $nue){
        try{
            $agregmiado = agregmiado::where('NUE', $nue)->first();
            $agremiadoUser = User::where('NUE', $nue)->first();

            if(!$agregmiado){
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontro el agregmiado',
                    'errors' => null
                ], 404);
            }

            $validateUser = Validator::make($request->all(),[
                'nombre' => 'string|max:255',
                'apellido_p' => 'string|max:255',
                'apellido_m' => 'string|max:255',
                'id_sexo' => 'integer',
                'NUP' => 'string|max:10',
                'NUE' => 'string|max:10',
                'RFC' => 'string|max:13',
                'NSS' => 'string|max:50',
                'fecha_nacimiento' => 'string|max:255',
                // 'telefono' => ,
                'cuota' => 'integer',
                'id_rol' => 'integer'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'Error en la validacion de datos',
                    'errors' => $validateUser->errors()
                ], 422);
            }

            $agregmiado->update([
                'nombre' => $request->get('nombre'),
                'apellido_p' => $request->get('apellido_p'),
                'apellido_m' => $request->get('apellido_m'),
                'id_sexo' => $request->get('id_sexo'),
                'NUP' => $request->get('NUP'),
                'NUE' => $request->get('NUE'),
                'RFC' => $request->get('RFC'),
                'NSS' => $request->get('NSS'),
                'fecha_nacimiento' => $request->get('fecha_nacimiento'),
                'telefono' => $request->get('telefono'),
                'cuota' => $request->get('cuota'),
                'id_rol' => $request->get('id_rol')
            ]);

            $agremiadoUser->update([
                'name' => $request->nombre,
                'NUE'=> $request->NUE,
                'id_agregmiado' => $agregmiado->id,
                'password' => bcrypt($request->NUE),
                'id_rol' => $request->id_rol
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Agregmiado actualizado correctamente',
                'data' => [
                    'agregmiado' => $agregmiado,
                    'user' => $agremiadoUser
                ]
            ], 200);

        } catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el agregmiado',
                'errors' => $th->getMessage()
            ], 500);
        }

    }

    public function deleteAgregmiado($id){
        try{
            $agregmiado = agregmiado::find($id);
            $agremiadoUser = User::where('id_agregmiado', $id)->first();

            if(!$agregmiado){
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontro el agregmiado',
                    'errors' => null
                ], 404);
            }

            $agremiadoUser->delete();
            $agregmiado->delete();

            return response()->json([
                'success' => true,
                'message' => 'Agregmiado eliminado correctamente',
                'data' => [
                    'agregmiado' => $agregmiado,
                    'user' => $agremiadoUser
                ]
            ], 200);

        } catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el agregmiado',
                'errors' => $th->getMessage()
            ], 500);
        }
    }



}
