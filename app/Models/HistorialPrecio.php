<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialPrecio extends Model
{
    protected $table   = 'historial_precios';
    public $timestamps = false;

    protected $fillable = [
        'id_producto', 'precio_anterior', 'precio_nuevo', 'motivo', 'cambiado_en'
    ];

    protected $casts = [
        'cambiado_en'     => 'datetime',
        'precio_anterior' => 'decimal:2',
        'precio_nuevo'    => 'decimal:2',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}