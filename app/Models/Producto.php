<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'tipo_aceite',
        'imagen_url',
        'disponible',
        'precio'
    ];

    public function detallesCotizacion()
    {
        return $this->hasMany(DetalleCotizacion::class, 'id_producto');
    }
}