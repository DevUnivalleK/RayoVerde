<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CotizacionController extends Controller
{
    // Historial de cotizaciones del usuario autenticado
    public function historial()
    {
        // Buscar por id_cliente o id_usuario según tu relación
        $clienteId = Auth::user()->id_cliente ?? Auth::id();
        
        $cotizaciones = Cotizacion::where('id_cliente', $clienteId)
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
        $clienteId = Auth::user()->id_cliente ?? Auth::id();
        
        $cotizacion = Cotizacion::where('id_cliente', $clienteId)
            ->where('id_cotizacion', $id)
            ->firstOrFail();
        
        return response()->json([
            'success' => true,
            'data' => $cotizacion
        ]);
    }
}