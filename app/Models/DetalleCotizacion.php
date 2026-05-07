<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCotizacion extends Model
{
    protected $table      = 'detalle_cotizaciones';
    protected $primaryKey = 'id_detalle';
    public $timestamps    = false;

    protected $fillable = [
        'id_cotizacion', 'id_producto',
        'volumen_litros', 'precio_unitario', 'descuento_pct', 'subtotal'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}