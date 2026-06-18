<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CotizacionExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $cotizacion;

    public function __construct($cotizacion)
    {
        $this->cotizacion = $cotizacion;
    }

    public function array(): array
    {
        $data = [];
        
        // Formateo de fecha corregido para Bolivia
        $fecha = Carbon::parse($this->cotizacion->generado_en)
                       ->setTimezone('America/La_Paz')
                       ->format('d/m/Y H:i');
                       

        // Título
        $data[] = ['RAYO VERDE - COTIZACIÓN'];
        $data[] = [];
        
        // Información de la cotización
        $data[] = ['CÓDIGO:', $this->cotizacion->codigo];
        $data[] = ['FECHA:', $fecha];
        
        
        // Acceso a la empresa mediante la nueva relación 'cliente'
        $empresa = $this->cotizacion->cliente ? $this->cotizacion->cliente->empresa : 'N/A';
        $data[] = ['CLIENTE:', $empresa];
        
        $data[] = ['VÁLIDO HASTA:', $this->cotizacion->vencimiento ?? '30 días'];
        $data[] = [];
        
        // Encabezados de productos
        $data[] = ['PRODUCTOS'];
        $data[] = ['Producto', 'Volumen (Litros)', 'Precio Unitario (Bs)', 'Subtotal (Bs)'];
        
        // Detalle de productos
        foreach ($this->cotizacion->detalles as $detalle) {
            $data[] = [
                $detalle->producto->nombre ?? 'Producto',
                ($detalle->volumen_litros ?? 0) . ' L',
                number_format($detalle->precio_unitario ?? 0, 2),
                number_format($detalle->subtotal ?? 0, 2)
            ];
        }
        
        $data[] = [];
        
        // Resumen
        $data[] = ['RESUMEN DE LA COTIZACIÓN'];
        $data[] = ['Subtotal:', '', '', 'Bs ' . number_format($this->cotizacion->subtotal ?? 0, 2)];
        $data[] = ['Descuento:', '', '', 'Bs ' . number_format($this->cotizacion->descuento_aplicado ?? 0, 2)];
        $data[] = ['Envío:', '', '', 'Bs ' . number_format($this->cotizacion->costo_envio_snapshot ?? 0, 2)];
        $data[] = ['TOTAL:', '', '', 'Bs ' . number_format($this->cotizacion->total ?? 0, 2)];
        
        $data[] = [];
        $data[] = ['¡Gracias por confiar en Rayo Verde!'];
        
        return $data;
    }

    public function headings(): array { return []; }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '006c0f']], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        
        $sheet->getStyle('A7:D7')->applyFromArray(['font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '006c0f']]]);
        
        $sheet->getStyle('A8:D8')->applyFromArray(['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DAEED7']], 'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]]);
        
        // Alinear montos a la derecha
        $sheet->getStyle('C9:D100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        return [];
    }
}