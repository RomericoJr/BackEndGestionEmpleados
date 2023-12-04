<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject

{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'password',
        'NUE',
        'id_rol',
        'id_agregmiado'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey(); // Debe devolver la clave primaria del usuario (por defecto, el ID).
    }

    public function getJWTCustomClaims()
    {
        return []; // Puedes agregar datos adicionales en este arreglo si lo necesitas.
    }

    public function rol(){
        return $this->belongsTo(rol::class, 'id_rol');
    }

    public function agregmiado(){
        return $this->belongsTo(agregmiado::class, 'id_agregmiado');
    }


}
