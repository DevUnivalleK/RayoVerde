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



public function enviarCorreo($id)
    {
        $cotizacion = Cotizacion::with(['detalles.producto', 'usuario', 'estado'])
                                ->findOrFail($id);

        try {
            $this->enviarAlAdmin($cotizacion);
            return back()->with('success', 'Correo enviado al administrador correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo enviar el correo: ' . $e->getMessage());
        }
    }

private function enviarAlAdmin(Cotizacion $cotizacion): void
    {
        // Primero busca por tabla usuario_roles
        $admin = Usuario::whereHas('roles', function ($q) {
            $q->whereIn('nombre', ['admin', 'administrador', 'Administrador', 'Admin']);
        })->first();

        // Fallback: busca por campo rol directo en usuarios
        if (!$admin) {
            $admin = Usuario::where('rol', 'admin')
                            ->orWhere('rol', 'administrador')
                            ->first();
        }

        if ($admin && $admin->correo) {
            Mail::to($admin->correo)->send(new CotizacionMail($cotizacion));
        }
    }

/*
    |--------------------------------------------------------------------------
    | ADICIONES NUEVAS: MÓDULO DE PERSONAL DE VENTAS (MANTENER INTACTO LO ANTERIOR)
    |--------------------------------------------------------------------------
    */

    public function indexVentas()
    {
        return view('PersonalVentas.cotizaciones');
    }

    public function dataVentas()
{
    $cotizaciones = \App\Models\Cotizacion::with(['usuario', 'estado'])
                        ->orderBy('generado_en', 'desc')
                        ->get();

    $estadosReales = \App\Models\EstadoCotizacion::all();

    return response()->json([
        'success' => true,
        'data' => $cotizaciones,
        'estados_sistema' => $estadosReales 
    ]);
}

    public function actualizarEstadoVentas(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'id_estado' => 'required|integer'
        ]);

        $cotizacion = \App\Models\Cotizacion::findOrFail($id);
        $cotizacion->id_estado = $request->id_estado;
        $cotizacion->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado por el módulo de ventas.'
        ]);
    }
public function obtenerDetalleVentas($id)
    {
        $cotizacion = \App\Models\Cotizacion::findOrFail($id);

        $detalles = \Illuminate\Support\Facades\DB::table('detalle_cotizaciones')
            ->where('id_cotizacion', $id)
            ->get();

        foreach ($detalles as $detalle) {
            $producto = \Illuminate\Support\Facades\DB::table('productos')
                ->where('id_producto', $detalle->id_producto)
                ->first();
            
            $detalle->producto = $producto ? ['nombre' => $producto->nombre] : ['nombre' => 'Producto #' . $detalle->id_producto];
        }

        return response()->json([
            'success' => true,
            'cotizacion' => $cotizacion,
            'detalles' => $detalles
        ]);
    }

}