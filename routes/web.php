<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CotizacionController;

Route::get('/', function () {
    return view('welcome');
});
// ============================================
// RUTAS PARA COTIZACIONES - [FE-01]
// ============================================
Route::get('/cotizaciones/historial', [CotizacionController::class, 'historial']);
Route::get('/cotizaciones/{id}', [CotizacionController::class, 'show']);
