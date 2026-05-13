<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoCotizacion extends Model
{
    protected $table = 'estados_cotizacion';
    protected $primaryKey = 'id_estado';
    public $timestamps = false;
    
    protected $fillable = ['nombre'];
}
class PedidoPendiente extends Model
{
    protected $table    = 'pedidos_pendientes';
    protected $fillable = [
        'id_cliente', 'codigo', 'total',
        'nombre_titular', 'banco', 'carrito',
        'estado', 'confirmado_en', 'revisado_en',
    ];

    protected $casts = [
        'carrito'       => 'array',
        'confirmado_en' => 'datetime',
        'revisado_en'   => 'datetime',
        'total'         => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }
    public function estado()
{
    return $this->belongsTo(EstadoCotizacion::class, 'id_estado', 'id_estado');
}
}