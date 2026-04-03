<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'empresa',
        'telefono',
        'ciudad',
        'region',
        'nombre_completo',
        'direccion'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'id_cliente');
    }
}
