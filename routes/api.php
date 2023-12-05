<?php

use App\Http\Controllers\AgregmiadoController;
use App\Http\Controllers\SexController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'jwt.auth',
], function ($router) {
    Route::get('me', [UserController::class, 'userDetails']);
    Route::post('logout', [UserController::class, 'logout']);

    //create new Agremiados
    Route::post('newAgregmiado', [AgregmiadoController::class, 'registerAgregmiado']);
    //get all Agremiados
    Route::get('getAgremiados', [AgregmiadoController::class, 'getAgregmiado']);
    //get Agremiado by id
    Route::get('getAgregmiadoByNUE/{NUE}', [AgregmiadoController::class, 'getAgregmiadoByNUE']);
    //delete Agremiado by id
    Route::delete('deleteAgremiado/{id}', [AgregmiadoController::class, 'deleteAgregmiado']);
    //update Agremiado by id
    Route::put('updateAgremiado/{nue}', [AgregmiadoController::class, 'updateAgregmiado']);

    //post solicitud
    Route::post('storeSolicitud', [SolicitudController::class, 'storeSolicitud']);
    //get all solicitudes
    Route::get('getSolicitud', [SolicitudController::class, 'getSolicitud']);
    //get solicitud by id
    Route::get('getSolicitudById/{id}', [SolicitudController::class, 'getSolicitudById']);
    //delete solicitud by id
    Route::delete('deleteSolicitud/{id}', [SolicitudController::class, 'deleteSolicitud']);

    Route::get('getGenere', [SexController::class, 'getGenere']);

    Route::get('/descargar-solicitud/{id}', [SolicitudController::class, 'descargarSolicitud']);
    Route::get('/descargar-solicitud/{archivo}', function ($archivo) {
        return Storage::response($archivo);
    })->where('archivo', '.*');
});


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
// Route::get('users', [UserController::class, 'getUser']);

