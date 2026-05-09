<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table      = 'productos';
    protected $primaryKey = 'id_producto';
    public $timestamps    = false;

    protected $fillable = [
        'nombre',
        'imagen_url',
        'precio',
        'cantidad',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'cantidad' => 'integer',
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_producto', 'id_producto');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCotizacion::class, 'id_producto', 'id_producto');
    }
}