<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    protected $primaryKey = 'id_cotizacion';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'id_usuario',
        'id_tarifa_envio',
        'id_estado',
        'subtotal',
        'total',
        'descuento_aplicado',
        'costo_envio_snapshot',
        'precio_por_litro',
        'generado_en',
        'vencimiento'
        
    ];


    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function tarifa()
    {
        return $this->belongsTo(TarifaEnvio::class, 'id_tarifa_envio');
    }

    public function detalles()
{
    return $this->hasMany(DetalleCotizacion::class, 'id_cotizacion', 'id_cotizacion');
}
public function estado()
{
    return $this->belongsTo(EstadoCotizacion::class, 'id_estado', 'id_estado');
}
}
