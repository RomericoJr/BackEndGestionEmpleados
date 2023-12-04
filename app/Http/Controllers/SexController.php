<?php

namespace App\Http\Controllers;

use App\Models\sex;
use Illuminate\Http\Request;

class SexController extends Controller
{
    public function getGenere(){
        $genere = sex::all();
        return response()->json($genere);
    }
}
