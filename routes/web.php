<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\ReporteController;
use App\Exports\ReporteExport;
use App\Http\Controllers\ChatbotWebhookController;
// Controladores de Autenticación y FAQ
use App\Http\Controllers\FaqController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LoginController;

// Controladores de Productos y Ventas (Trabajo de Tapia/Challapa)
use App\Http\Controllers\ProductoCatalogoController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\VentaController;
use App\Http\Controllers\PasswordRecoverController;

// Controladores de Carrito, Checkout y Bandeja de Pedidos
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\BandejaController;

// --- Rutas de Inicio ---


Route::get('/', function () {
    return view('auth.login');
})->name('login');


Route::get('/home', function () {
    return view('index'); 
})->name('home');


Route::get('/password', function () {
    return view('auth.password');
})->name('password');

Route::post('/password', [PasswordRecoverController::class, 'passwordRecover'])->name('password.recover');

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
Route::get('/chatbot', function () {
    return view('chatbot.ui'); 
})->name('chatbot.index');

Route::post('/chatbot/webhook', [ChatbotWebhookController::class, 'handle'])->name('chatbot.webhook');

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

// === RUTAS DE REPORTES (completas) ===
Route::get('/admin/reportes', function () {
    return view('Admin.reportes.general');
})->name('admin.reportes.index');

Route::get('/admin/reportes/general', function () {
    return view('Admin.reportes.general');
})->name('admin.reportes.general');

Route::get('/admin/reportes/por-fecha', function () {
    return view('Admin.reportes.por_fecha');
})->name('admin.reportes.porFecha');

Route::get('/admin/reportes/filtrado', function () {
    return view('Admin.reportes.filtrado');
})->name('admin.reportes.filtros');  // ← Este es el nombre que usa el sidebar

// API endpoints
Route::get('/admin/reportes/usuarios', [ReporteController::class, 'getUsuarios']);
Route::get('/admin/reportes/filtrado-data', [ReporteController::class, 'reporteFiltrado']);
Route::get('/admin/reportes/metricas', [ReporteController::class, 'metricas']);
Route::get('/admin/reportes/exportar.excel', [ReporteController::class, 'exportarExcel'])->name('admin.reportes.exportar.excel');
Route::get('/admin/reportes/exportar.pdf', [ReporteController::class, 'exportarPdf'])->name('admin.reportes.exportar.pdf');