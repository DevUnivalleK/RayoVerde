<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifaEnvio extends Model
{
    protected $table = 'tarifas_envio';
    protected $primaryKey = 'id_tarifa';
    public $timestamps = false;

    protected $fillable = [
        'region',
        'ciudad',
        'costo_envio',
        'activo'
    ];

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'id_tarifa_envio');
    }
}