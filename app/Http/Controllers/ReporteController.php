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
    
    // Métricas principales
    public function metricas(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', now());
        
        // Totales generales
        $totales = Cotizacion::whereBetween('generado_en', [$fechaInicio, $fechaFin])
            ->select(
                DB::raw('COUNT(*) as total_cotizaciones'),
                DB::raw('SUM(subtotal) as total_ventas'),
                DB::raw('AVG(subtotal) as promedio_venta'),
                DB::raw('SUM(descuento_aplicado) as total_descuentos')
            )
            ->first();
        
        // Top productos más cotizados
        $topProductos = DB::table('detalle_cotizaciones')
            ->join('productos', 'detalle_cotizaciones.id_producto', '=', 'productos.id_producto')
            ->join('cotizaciones', 'detalle_cotizaciones.id_cotizacion', '=', 'cotizaciones.id_cotizacion')
            ->whereBetween('cotizaciones.generado_en', [$fechaInicio, $fechaFin])
            ->select(
                'productos.id_producto',
                'productos.nombre',
                DB::raw('SUM(detalle_cotizaciones.cantidad) as total_cantidad'),
                DB::raw('SUM(detalle_cotizaciones.subtotal) as total_ventas')
            )
            ->groupBy('productos.id_producto', 'productos.nombre')
            ->orderBy('total_ventas', 'desc')
            ->limit(10)
            ->get();
        
        // Top clientes
        $topClientes = Cotizacion::whereBetween('generado_en', [$fechaInicio, $fechaFin])
            ->join('clientes', 'cotizaciones.id_cliente', '=', 'clientes.id_cliente')
            ->select(
                'clientes.id_cliente',
                'clientes.empresa',
                DB::raw('COUNT(cotizaciones.id_cotizacion) as total_cotizaciones'),
                DB::raw('SUM(cotizaciones.subtotal) as total_compras')
            )
            ->groupBy('clientes.id_cliente', 'clientes.empresa')
            ->orderBy('total_compras', 'desc')
            ->limit(10)
            ->get();
        
        // Cotizaciones por estado
        $porEstado = Cotizacion::whereBetween('generado_en', [$fechaInicio, $fechaFin])
            ->join('estados_cotizacion', 'cotizaciones.id_estado', '=', 'estados_cotizacion.id_estado')
            ->select(
                'estados_cotizacion.nombre_estado',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('estados_cotizacion.nombre_estado')
            ->get();
        
        return response()->json([
            'success' => true,
            'totales' => $totales,
            'top_productos' => $topProductos,
            'top_clientes' => $topClientes,
            'por_estado' => $porEstado,
            'fechas' => [
                'inicio' => $fechaInicio,
                'fin' => $fechaFin
            ]
        ]);
    }
    
    // Reporte de cotizaciones por fecha
    public function porFecha(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', now());
        
        $data = Cotizacion::whereBetween('generado_en', [$fechaInicio, $fechaFin])
            ->select(
                DB::raw('DATE(generado_en) as fecha'),
                DB::raw('COUNT(*) as total_cotizaciones'),
                DB::raw('SUM(subtotal) as total_ventas')
            )
            ->groupBy(DB::raw('DATE(generado_en)'))
            ->orderBy('fecha', 'asc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    // Reporte con filtros dinámicos
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
    if ($request->id_cliente) {
        $query->where('id_cliente', $request->id_cliente);
    }
    
    // Filtro por estado
    if ($request->id_estado) {
        $query->where('id_estado', $request->id_estado);
    }
    
    // Filtro por producto (a través de detalles)
    if ($request->id_producto) {
        $query->whereHas('detalles', function($q) use ($request) {
            $q->where('id_producto', $request->id_producto);
        });
    }
    
    $cotizaciones = $query->with(['cliente', 'estado', 'detalles.producto'])
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
        DB::raw('SUM(subtotal) as total_ventas')
    )
    ->groupBy(DB::raw('DATE(generado_en)'))
    ->orderBy('fecha', 'asc')
    ->get();
    
    // Lista de clientes para el filtro
    $clientes = Cliente::all();
    
    // Lista de productos para el filtro
    $productos = Producto::all();
    
    // Estados para el filtro
    $estados = DB::table('estados_cotizacion')->get();
    
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
            'total_ventas' => $cotizaciones->sum('subtotal'),
            'promedio' => $cotizaciones->avg('subtotal'),
            'total_descuentos' => $cotizaciones->sum('descuento_aplicado')
        ]
    ]);
}

// Exportar reporte a Excel
public function exportarExcel(Request $request)
{
    $fechaInicio = $request->fecha_inicio ?? now()->startOfMonth();
    $fechaFin = $request->fecha_fin ?? now();
    
    $cotizaciones = Cotizacion::whereBetween('generado_en', [$fechaInicio, $fechaFin])
        ->with(['cliente', 'estado'])
        ->get();
    
    return Excel::download(new ReporteExport($cotizaciones, $fechaInicio, $fechaFin), 'reporte_cotizaciones.xlsx');
}

// Exportar reporte a PDF
public function exportarPdf(Request $request)
{
    $fechaInicio = $request->fecha_inicio ?? now()->startOfMonth();
    $fechaFin = $request->fecha_fin ?? now();
    
    $cotizaciones = Cotizacion::whereBetween('generado_en', [$fechaInicio, $fechaFin])
        ->with(['cliente', 'estado'])
        ->get();
    
    $pdf = Pdf::loadView('pdf.reporte', compact('cotizaciones', 'fechaInicio', 'fechaFin'));
    return $pdf->download('reporte_cotizaciones.pdf');
}
// Exportar reporte detallado a PDF con gráficos
public function exportarPdfDetallado(Request $request)
{
    $fechaInicio = $request->fecha_inicio ?? now()->startOfMonth();
    $fechaFin = $request->fecha_fin ?? now();
    
    // Datos para el reporte
    $totales = Cotizacion::whereBetween('generado_en', [$fechaInicio, $fechaFin])
        ->select(
            DB::raw('COUNT(*) as total_cotizaciones'),
            DB::raw('SUM(subtotal) as total_ventas'),
            DB::raw('AVG(subtotal) as promedio'),
            DB::raw('SUM(descuento_aplicado) as total_descuentos')
        )
        ->first();
    
    $topProductos = DB::table('detalle_cotizaciones')
        ->join('productos', 'detalle_cotizaciones.id_producto', '=', 'productos.id_producto')
        ->join('cotizaciones', 'detalle_cotizaciones.id_cotizacion', '=', 'cotizaciones.id_cotizacion')
        ->whereBetween('cotizaciones.generado_en', [$fechaInicio, $fechaFin])
        ->select('productos.nombre', DB::raw('SUM(detalle_cotizaciones.subtotal) as total'))
        ->groupBy('productos.nombre')
        ->orderBy('total', 'desc')
        ->limit(10)
        ->get();
    
    $topClientes = Cotizacion::whereBetween('generado_en', [$fechaInicio, $fechaFin])
        ->join('clientes', 'cotizaciones.id_cliente', '=', 'clientes.id_cliente')
        ->select('clientes.empresa', DB::raw('COUNT(*) as total'), DB::raw('SUM(cotizaciones.subtotal) as compras'))
        ->groupBy('clientes.empresa')
        ->orderBy('compras', 'desc')
        ->limit(10)
        ->get();
    
    $evolucion = Cotizacion::whereBetween('generado_en', [$fechaInicio, $fechaFin])
        ->select(DB::raw('DATE(generado_en) as fecha'), DB::raw('COUNT(*) as total'), DB::raw('SUM(subtotal) as ventas'))
        ->groupBy(DB::raw('DATE(generado_en)'))
        ->orderBy('fecha', 'asc')
        ->get();
    
    $cotizaciones = Cotizacion::whereBetween('generado_en', [$fechaInicio, $fechaFin])
        ->with(['cliente', 'estado'])
        ->orderBy('generado_en', 'desc')
        ->get();
    
    $pdf = Pdf::loadView('pdf.reporte_detallado', compact(
        'fechaInicio', 'fechaFin', 'totales', 'topProductos', 
        'topClientes', 'evolucion', 'cotizaciones'
    ));
    
    return $pdf->download("reporte_{$fechaInicio}_al_{$fechaFin}.pdf");
}

// Endpoint para datos en tiempo real (polling)
public function datosRealtime(Request $request)
{
    $ultimaActualizacion = $request->ultima_actualizacion;
    
    $query = Cotizacion::query();
    
    if ($request->fecha_inicio) {
        $query->whereDate('generado_en', '>=', $request->fecha_inicio);
    }
    if ($request->fecha_fin) {
        $query->whereDate('generado_en', '<=', $request->fecha_fin);
    }
    
    // Solo traer cambios desde la última actualización
    if ($ultimaActualizacion) {
        $query->where('updated_at', '>', $ultimaActualizacion)
              ->orWhere('created_at', '>', $ultimaActualizacion);
    }
    
    $nuevasCotizaciones = $query->with(['cliente', 'estado'])->get();
    
    // Métricas actualizadas
    $metricas = Cotizacion::when($request->fecha_inicio, function($q) use ($request) {
            $q->whereDate('generado_en', '>=', $request->fecha_inicio);
        })
        ->when($request->fecha_fin, function($q) use ($request) {
            $q->whereDate('generado_en', '<=', $request->fecha_fin);
        })
        ->select(
            DB::raw('COUNT(*) as total_cotizaciones'),
            DB::raw('SUM(subtotal) as total_ventas'),
            DB::raw('AVG(subtotal) as promedio'),
            DB::raw('SUM(descuento_aplicado) as total_descuentos')
        )
        ->first();
    
    return response()->json([
        'success' => true,
        'hay_novedades' => $nuevasCotizaciones->count() > 0,
        'nuevas_cotizaciones' => $nuevasCotizaciones,
        'metricas' => $metricas,
        'timestamp' => now()
    ]);
}
}