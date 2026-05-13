<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReporteExport;

class ReporteController extends Controller
{
    // Vista principal del panel
    public function index()
    {
        return view('reportes.dashboard');
    }
    
    // Reporte con filtros dinámicos (VERSIÓN CORREGIDA)
    public function reporteFiltrado(Request $request)
    {
        $query = Cotizacion::query();
        
        // Filtro por fechas
        if ($request->fecha_inicio) {
            $query->whereDate('generado_en', '>=', $request->fecha_inicio);
        }
        if ($request->fecha_fin) {
            $query->whereDate('generado_en', '<=', $request->fecha_fin);
        }
        
        // Filtro por cliente
        if ($request->id_usuario) {
            $query->where('id_usuario', $request->id_usuario);
        }
        
        // Filtro por producto (a través de detalles)
        if ($request->id_producto) {
            $query->whereHas('detalles', function($q) use ($request) {
                $q->where('id_producto', $request->id_producto);
            });
        }
        
        $cotizaciones = $query->with(['usuario', 'detalles.producto'])
            ->orderBy('generado_en', 'desc')
            ->get();
        
        // Evolución por fecha (para gráfico de líneas)
        $evolucion = Cotizacion::whereBetween('generado_en', [
            $request->fecha_inicio ?? now()->startOfMonth(),
            $request->fecha_fin ?? now()
        ])
        ->select(
            DB::raw('DATE(generado_en) as fecha'),
            DB::raw('COUNT(*) as total'),
            DB::raw('COALESCE(SUM(subtotal), 0) as total_ventas')
        )
        ->groupBy(DB::raw('DATE(generado_en)'))
        ->orderBy('fecha', 'asc')
        ->get();
        
        // Lista de clientes para el filtro
        $clientes = Cliente::select('id_cliente', 'empresa')->get();
        
        // Lista de productos para el filtro
        $productos = Producto::select('id_producto', 'nombre')->get();
        
        // Estados para el filtro
        $estados = DB::table('estados_cotizacion')->select('id_estado', 'nombre')->get();
        
        // Calcular resumen
        $totalVentas = $cotizaciones->sum('subtotal');
        
        return response()->json([
            'success' => true,
            'cotizaciones' => $cotizaciones,
            'evolucion' => $evolucion,
            'filtros' => [
                'clientes' => $clientes,
                'productos' => $productos,
                'estados' => $estados
            ],
            'resumen' => [
                'total_cotizaciones' => $cotizaciones->count(),
                'total_ventas' => $totalVentas,
                'promedio' => $cotizaciones->count() > 0 ? $totalVentas / $cotizaciones->count() : 0,
                'total_descuentos' => $cotizaciones->sum('descuento_aplicado')
            ]
        ]);
    }

    // Endpoint para filtros (mismo que reporteFiltrado pero solo para los selectores)
    public function filtros()
    {
        $clientes = Cliente::select('id_cliente', 'empresa')->get();
        $productos = Producto::select('id_producto', 'nombre')->get();
        $estados = DB::table('estados_cotizacion')->select('id_estado', 'nombre')->get();
        
        return response()->json([
            'success' => true,
            'filtros' => [
                'clientes' => $clientes,
                'productos' => $productos,
                'estados' => $estados
            ]
        ]);
    }
    
    // Exportar reporte a Excel
    public function exportarExcel(Request $request)
    {
        $fechaInicio = $request->fecha_inicio ?? now()->startOfMonth()->toDateString();
        $fechaFin = $request->fecha_fin ?? now()->toDateString();
        
        $cotizaciones = Cotizacion::whereBetween('generado_en', [$fechaInicio, $fechaFin])
            ->with(['usuario'])
            ->get();
        
        return Excel::download(new ReporteExport($cotizaciones, $fechaInicio, $fechaFin), 'reporte_cotizaciones.xlsx');
    }
    
    // Exportar reporte a PDF
    public function exportarPdf(Request $request)
    {
        $fechaInicio = $request->fecha_inicio ?? now()->startOfMonth()->toDateString();
        $fechaFin = $request->fecha_fin ?? now()->toDateString();
        
        $cotizaciones = Cotizacion::whereBetween('generado_en', [$fechaInicio, $fechaFin])
            ->with(['usuario'])
            ->get();
        
        $pdf = Pdf::loadView('pdf.reporte', compact('cotizaciones', 'fechaInicio', 'fechaFin'));
        return $pdf->download('reporte_cotizaciones.pdf');
    }
    
    // Exportar reporte detallado a PDF
    public function exportarPdfDetallado(Request $request)
    {
        $fechaInicio = $request->fecha_inicio ?? now()->startOfMonth()->toDateString();
        $fechaFin = $request->fecha_fin ?? now()->toDateString();
        
        $totales = Cotizacion::whereBetween('generado_en', [$fechaInicio, $fechaFin])
            ->select(
                DB::raw('COUNT(*) as total_cotizaciones'),
                DB::raw('COALESCE(SUM(subtotal), 0) as total_ventas'),
                DB::raw('COALESCE(AVG(subtotal), 0) as promedio')
            )
            ->first();
        
        $cotizaciones = Cotizacion::whereBetween('generado_en', [$fechaInicio, $fechaFin])
            ->with(['usuario', 'detalles.producto'])
            ->get();
        
        $pdf = Pdf::loadView('pdf.reporte_detallado', compact('cotizaciones', 'fechaInicio', 'fechaFin', 'totales'));
        return $pdf->download('reporte_detallado.pdf');
    }
    
    // Endpoint para datos en tiempo real
    public function datosRealtime(Request $request)
    {
        return response()->json([
            'success' => true,
            'hay_novedades' => false,
            'timestamp' => now()
        ]);
    }
    
    // Métricas principales (simplificado)
    public function metricas(Request $request)
    {
        $fechaInicio = $request->fecha_inicio ?? now()->startOfMonth()->toDateString();
        $fechaFin = $request->fecha_fin ?? now()->toDateString();
        
        $totales = Cotizacion::whereBetween('generado_en', [$fechaInicio, $fechaFin])
            ->select(
                DB::raw('COUNT(*) as total_cotizaciones'),
                DB::raw('COALESCE(SUM(subtotal), 0) as total_ventas'),
                DB::raw('COALESCE(AVG(subtotal), 0) as promedio_venta'),
                DB::raw('COALESCE(SUM(descuento_aplicado), 0) as total_descuentos')
            )
            ->first();
        
        return response()->json([
            'success' => true,
            'totales' => $totales
        ]);
    }
    public function getUsuarios()
{
    $usuarios = DB::table('usuarios')->select('id_usuario', 'nombre', 'apellido')->get();
    return response()->json($usuarios);
}
}