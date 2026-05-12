<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}