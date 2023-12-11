<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class solicitud extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_file',
        'id_agregmiado'
    ];

    public function agregmiado()
    {
        return $this->belongsTo(agregmiado::class, 'id_agregmiado');
    }

    public function solicitud()
    {
        return $this->belongsTo(solicitud::class);
    }
}
