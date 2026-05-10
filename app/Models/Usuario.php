<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'password_hash',
        'rol',
        'activo',
        'respuesta_secreta'
    ];

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'id_usuario');
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'id_usuario');
    }
}
