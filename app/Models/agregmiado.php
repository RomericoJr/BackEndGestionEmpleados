<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class agregmiado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido_p',
        'apellido_m',
        'id_sexo',
        'NUP',
        'NUE',
        'RFC',
        'NSS',
        'fecha_nacimiento',
        'telefono',
        'cuota',
        'id_rol',
    ];
}
