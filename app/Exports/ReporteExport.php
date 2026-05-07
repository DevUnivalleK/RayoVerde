<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReporteExport implements FromCollection, WithHeadings, WithMapping
{
    protected $cotizaciones;
    protected $fechaInicio;
    protected $fechaFin;
    
    public function __construct($cotizaciones, $fechaInicio, $fechaFin)
    {
        $this->cotizaciones = $cotizaciones;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }
    
    public function collection()
    {
        return $this->cotizaciones;
    }
    
    public function headings(): array
    {
        return [
            'CÓDIGO',
            'FECHA',
            'CLIENTE',
            'SUBTOTAL',
            'DESCUENTO',
            'TOTAL',
            'ESTADO'
        ];
    }
    
    public function map($cotizacion): array
    {
        return [
            $cotizacion->codigo,
            $cotizacion->generado_en,
            $cotizacion->cliente->empresa ?? 'N/A',
            $cotizacion->subtotal,
            $cotizacion->descuento_aplicado,
            $cotizacion->total,
            $cotizacion->estado->nombre_estado ?? 'Pendiente'
        ];
    }
}