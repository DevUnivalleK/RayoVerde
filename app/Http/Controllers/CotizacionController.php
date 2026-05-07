<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CotizacionExport;
use App\Models\Cotizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CotizacionController extends Controller
{
    public function historial()
{
    // Temporalmente usamos un ID de cliente fijo para probar
    $clienteId = 2; // Cambia a un ID que sepas que tiene cotizaciones
    
    $cotizaciones = Cotizacion::where('id_cliente', $clienteId)
        ->orderBy('generado_en', 'desc')
        ->get();
    
    \Log::info('Cotizaciones encontradas:', ['count' => $cotizaciones->count(), 'data' => $cotizaciones->toArray()]);
    
    return response()->json([
        'success' => true,
        'data' => $cotizaciones,
        'total' => $cotizaciones->count()
    ]);
}
    
    // Ver detalle de una cotización específica
    public function show($id)
    {
        $clienteId = Auth::user()->id_cliente ?? Auth::id();
        
        $cotizacion = Cotizacion::where('id_cliente', $clienteId)
            ->where('id_cotizacion', $id)
            ->firstOrFail();
        
        return response()->json([
            'success' => true,
            'data' => $cotizacion
        ]);
    }

    // Generar PDF de una cotización
public function generarPDF($id)
{
    $clienteId = Auth::user()->id_cliente ?? Auth::id();
    
    $cotizacion = Cotizacion::where('id_cliente', $clienteId)
        ->where('id_cotizacion', $id)
        ->with(['cliente', 'detalles'])
        ->firstOrFail();
    
    $pdf = Pdf::loadView('pdf.cotizacion', compact('cotizacion'));
    return $pdf->download("cotizacion_{$cotizacion->codigo}.pdf");
}

// Generar Excel de una cotización
public function generarExcel($id)
{
    $clienteId = Auth::user()->id_cliente ?? Auth::id();
    
    $cotizacion = Cotizacion::where('id_cliente', $clienteId)
        ->where('id_cotizacion', $id)
        ->firstOrFail();
    
    return Excel::download(new CotizacionExport($cotizacion), "cotizacion_{$cotizacion->codigo}.xlsx");
}
}