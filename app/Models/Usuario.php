<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'rol',
        'activo',
        'password_hash',
        'respuesta_secreta'
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'id_usuario');
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'id_usuario');
    }
}