<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\ReporteController;
use App\Exports\ReporteExport;

Route::get('/', function () {
    return view('welcome');
});
// ============================================
// RUTAS PARA COTIZACIONES - [FE-01]
// ============================================
Route::get('/cotizaciones/historial', [CotizacionController::class, 'historial']);
Route::get('/cotizaciones/{id}', [CotizacionController::class, 'show']);

// Vista del historial
Route::get('/mis-cotizaciones', function () {
    return view('cotizaciones.historial');
});

// Descargas
Route::get('/cotizaciones/historial', [CotizacionController::class, 'historial']);
Route::get('/cotizaciones/{id}', [CotizacionController::class, 'show']);
Route::get('/cotizaciones/{id}/pdf', [CotizacionController::class, 'generarPDF']);
Route::get('/cotizaciones/{id}/excel', [CotizacionController::class, 'generarExcel']);

// Reportes
Route::get('/reportes', [ReporteController::class, 'index']);
Route::get('/api/reportes/metricas', [ReporteController::class, 'metricas']);
Route::get('/api/reportes/por-fecha', [ReporteController::class, 'porFecha']);

// Reportes avanzados
Route::get('/api/reportes/filtrado', [ReporteController::class, 'reporteFiltrado']);
Route::get('/api/reportes/filtros', [ReporteController::class, 'reporteFiltrado']); // Para los selectores
Route::get('/api/reportes/exportar-excel', [ReporteController::class, 'exportarExcel']);
Route::get('/api/reportes/exportar-pdf', [ReporteController::class, 'exportarPdf']);

// Live updates
Route::get('/api/reportes/realtime', [ReporteController::class, 'datosRealtime']);
Route::get('/api/reportes/exportar-pdf-detallado', [ReporteController::class, 'exportarPdfDetallado']);