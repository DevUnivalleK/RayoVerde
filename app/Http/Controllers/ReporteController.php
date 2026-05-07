<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}