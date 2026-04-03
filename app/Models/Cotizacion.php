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
        'id_cliente',
        'id_usuario',
        'id_tarifa_envio',
        'subtotal',
        'descuento_aplicado',
        'costo_envio',
        'total',
        'estado',
        'vencimiento',
        'precio_por_litro'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function tarifa()
    {
        return $this->belongsTo(TarifaEnvio::class, 'id_tarifa_envio');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCotizacion::class, 'id_cotizacion');
    }
}
