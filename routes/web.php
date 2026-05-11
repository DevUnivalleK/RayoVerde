<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\ReporteController;
use App\Exports\ReporteExport;

// Controladores de Autenticación y FAQ
use App\Http\Controllers\FaqController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LoginController;

// Controladores de Productos y Ventas (Trabajo de Tapia/Challapa)
use App\Http\Controllers\ProductoCatalogoController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\VentaController;

// Controladores de Carrito, Checkout y Bandeja de Pedidos
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\BandejaController;

// --- Rutas de Inicio ---
Route::get('/', function () {
    return view('index'); 
})->name('home');

// --- Autenticación ---
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/registro', function () {
    return view('auth.register');
})->name('registro');

Route::post('/registro', [RegistroController::class, 'registrar'])->name('registrar.post');

// --- Dashboards ---
Route::get('/dashboard', function () {
    if (!session()->has('usuario_id')) {
        return redirect()->route('login');
    }
    return "Bienvenido " . session('usuario_nombre');
})->name('dashboard');

Route::get('/admin', function () {
    return view('admin-dashboard');
})->name('admin.dashboard');

// --- Chatbot (Rutas Duales) ---
Route::get('/chatbot-ui', function () {
    return view('chatbot.index'); 
})->name('chatbot.ui');

Route::get('/chatbot', function () {
    return view('chatbot.ui'); 
})->name('chatbot.index');

// --- Catálogo Público ---
Route::get('/catalogo', [ProductoCatalogoController::class, 'index'])->name('catalogo.index');

// --- Administración (Grupos con Prefijo) ---

// Gestión de FAQ
Route::prefix('admin')->group(function () {
    Route::get('/faq', [FaqController::class, 'index'])->name('admin.faq.index');
    Route::post('/faq', [FaqController::class, 'store'])->name('admin.faq.store');
    Route::put('/faq/{id}', [FaqController::class, 'update'])->name('admin.faq.update');
    Route::delete('/faq/{id}', [FaqController::class, 'destroy'])->name('admin.faq.destroy');
});

// Gestión de Productos
Route::prefix('admin/productos')->name('admin.productos.')->group(function () {
    Route::get('/',           [ProductoController::class, 'index'])->name('index');
    Route::get('/crear',      [ProductoController::class, 'create'])->name('crear');
    Route::post('/',          [ProductoController::class, 'store'])->name('store');
    Route::get('/{id}/editar',  [ProductoController::class, 'edit'])->name('editar');
    Route::put('/{id}',         [ProductoController::class, 'update'])->name('update');
    Route::delete('/{id}',      [ProductoController::class, 'destroy'])->name('destroy');
});

// Gestión de Ventas
Route::prefix('admin/ventas')->name('admin.ventas.')->group(function () {
    Route::get('/', [VentaController::class, 'index'])->name('index');
    Route::post('/', [VentaController::class, 'store'])->name('store');
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
// Reportes y Reportes avanzados (Organizados en grupo)
Route::prefix('admin/reportes')->name('admin.reportes.')->group(function () {
    
    // Esta es la ruta que soluciona tu error: admin.reportes.index
    Route::get('/', [ReporteController::class, 'index'])->name('index');

    // APIs y métricas
    Route::get('/metricas', [ReporteController::class, 'metricas'])->name('metricas');
    Route::get('/por-fecha', [ReporteController::class, 'porFecha'])->name('porFecha');
    Route::get('/filtrado', [ReporteController::class, 'reporteFiltrado'])->name('filtrado');
    Route::get('/filtros', [ReporteController::class, 'reporteFiltrado'])->name('filtros');
    
    // Exportaciones
    Route::get('/exportar-excel', [ReporteController::class, 'exportarExcel'])->name('exportar.excel');
    Route::get('/exportar-pdf', [ReporteController::class, 'exportarPdf'])->name('exportar.pdf');
    Route::get('/exportar-pdf-detallado', [ReporteController::class, 'exportarPdfDetallado'])->name('exportar.pdf.detallado');

    // Live updates
    Route::get('/realtime', [ReporteController::class, 'datosRealtime'])->name('realtime');
});




// CARRITO Y CHECKOUT

Route::middleware('auth')->group(function () {

    Route::get('/cliente/catalogo', function () {
        $productos = \App\Models\Producto::where('cantidad', '>', 0)
                                          ->orderBy('nombre')->get();
        return view('cliente.catalogo-cliente', compact('productos'));
    })->name('cliente.catalogo');

    Route::get('/cliente/carrito',
        [CarritoController::class, 'index'])->name('cliente.carrito');

    Route::post('/cliente/carrito/{id}',
        [CarritoController::class, 'agregar'])->name('cliente.carrito.agregar');

    Route::delete('/cliente/carrito/{id}',
        [CarritoController::class, 'quitar'])->name('cliente.carrito.quitar');

    Route::delete('/cliente/carrito',
        [CarritoController::class, 'vaciar'])->name('cliente.carrito.vaciar');

    Route::get('/cliente/checkout',
        [CheckoutController::class, 'formulario'])->name('cliente.checkout');

    Route::post('/cliente/checkout/pagar',
        [CheckoutController::class, 'procesarPago'])->name('cliente.checkout.pagar');

    Route::post('/cliente/checkout/confirmar',
        [CheckoutController::class, 'confirmarPago'])->name('cliente.checkout.confirmar');
});

// BANDEJA ADMIN PEDIDOS

Route::prefix('admin/pedidos')->name('admin.pedidos.')->group(function () {

    Route::get('/',
        [BandejaController::class, 'index'])->name('bandeja');

    Route::post('/{id}/aceptar',
        [BandejaController::class, 'aceptar'])->name('aceptar');

    Route::post('/{id}/rechazar',
        [BandejaController::class, 'rechazar'])->name('rechazar');

    Route::post('/cotizacion/{id}/completar',
        [BandejaController::class, 'completar'])->name('completar');
});