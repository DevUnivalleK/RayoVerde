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
    // Cambiar id_cliente por id_usuario
    $usuarioId = Auth::user()->id_usuario ?? Auth::id();
    
    $cotizaciones = Cotizacion::where('id_usuario', $usuarioId)
        ->orderBy('generado_en', 'desc')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $cotizaciones,
        'total' => $cotizaciones->count()
    ]);
}
    
    // Ver detalle de una cotización específica
    public function show($id)
{
    $usuarioId = Auth::user()->id_usuario ?? Auth::id();
    
    $cotizacion = Cotizacion::where('id_usuario', $usuarioId)
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
    $cotizacion = Cotizacion::findOrFail($id);
    
    $pdf = Pdf::loadView('pdf.cotizacion', compact('cotizacion'));
    return $pdf->download("cotizacion_{$cotizacion->codigo}.pdf");
}

// Generar Excel de una cotización
public function generarExcel($id)
{
    $cotizacion = Cotizacion::findOrFail($id);
    
    return Excel::download(new CotizacionExport($cotizacion), "cotizacion_{$cotizacion->codigo}.xlsx");
}
}