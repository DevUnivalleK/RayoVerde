<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

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
        
        // Título
        $data[] = ['RAYO VERDE - COTIZACIÓN'];
        $data[] = [];
        
        // Información de la cotización
        $data[] = ['CÓDIGO:', $this->cotizacion->codigo];
        $data[] = ['FECHA:', $this->cotizacion->generado_en];
        $data[] = ['CLIENTE:', $this->cotizacion->cliente->empresa ?? 'N/A'];
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

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo para el título
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '006c0f']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        
        // Estilo para la sección "PRODUCTOS"
        $sheet->getStyle('A7:D7')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '006c0f']],
            'font' => ['color' => ['rgb' => 'FFFFFF']]
        ]);
        
        // Estilo para encabezados de tabla
        $sheet->getStyle('A8:D8')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DAEED7']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        
        // Estilo para los datos de la tabla
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A9:D' . ($lastRow - 7))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        
        // Estilo para la sección "RESUMEN"
        $resumenRow = $lastRow - 6;
        $sheet->getStyle('A' . $resumenRow . ':D' . $resumenRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '006c0f']],
            'font' => ['color' => ['rgb' => 'FFFFFF']]
        ]);
        
        // Estilo para el TOTAL
        $totalRow = $lastRow - 2;
        $sheet->getStyle('A' . $totalRow . ':D' . $totalRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '64b863']]
        ]);
        
        // Estilo para el mensaje final
        $sheet->getStyle('A' . $lastRow . ':D' . $lastRow)->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '666666']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        $sheet->mergeCells('A' . $lastRow . ':D' . $lastRow);
        
        // Alinear montos a la derecha
        $sheet->getStyle('C9:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        return [];
    }
}