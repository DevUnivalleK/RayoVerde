<?php

namespace App\Exports;

use App\Models\Cotizacion;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CotizacionExport implements FromArray, WithHeadings
{
    protected $cotizacion;

    public function __construct($cotizacion)
    {
        $this->cotizacion = $cotizacion;
    }

    public function array(): array
    {
        $data = [];
        
        $data[] = ['COTIZACIÓN', $this->cotizacion->codigo];
        $data[] = ['Fecha', $this->cotizacion->generado_en];
        $data[] = ['Cliente', $this->cotizacion->cliente->nombre ?? 'N/A'];
        $data[] = [];
        $data[] = ['PRODUCTOS'];
        $data[] = ['Producto', 'Cantidad', 'Precio Unitario', 'Subtotal'];
        
        foreach ($this->cotizacion->detalles as $detalle) {
            $data[] = [
                $detalle->producto->nombre ?? 'Producto',
                $detalle->cantidad,
                '$' . number_format($detalle->precio_unitario, 2),
                '$' . number_format($detalle->subtotal, 2)
            ];
        }
        
        $data[] = [];
        $data[] = ['Subtotal', '', '', '$' . number_format($this->cotizacion->subtotal, 2)];
        $data[] = ['Descuento', '', '', '$' . number_format($this->cotizacion->descuento_aplicado, 2)];
        $data[] = ['Envío', '', '', '$' . number_format($this->cotizacion->costo_envio_snapshot, 2)];
        $data[] = ['TOTAL', '', '', '$' . number_format($this->cotizacion->total, 2)];
        
        return $data;
    }

    public function headings(): array
    {
        return [];
    }
}