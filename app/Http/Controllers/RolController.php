<?php

namespace App\Http\Controllers;

use App\Models\rol;
use Illuminate\Http\Request;

class RolController extends Controller
{

    public function store(Request $request)
    {
        $rol = rol::create($request->all());
        return response()->json($rol, 201);
    }


}
