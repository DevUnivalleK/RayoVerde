<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controladores
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\PasswordRecoverController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ChatbotWebhookController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductoCatalogoController;
use App\Http\Controllers\ChatVentasController;

// Controladores de Administración
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\VentaController;
use App\Http\Controllers\Admin\BandejaController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Sin Autenticación)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/registro', function () {
    return view('auth.register');
})->name('registro');
Route::post('/registro', [RegistroController::class, 'registrar'])->name('registrar.post');

Route::get('/password', function () {
    return view('auth.password');
})->name('password');
Route::post('/password', [PasswordRecoverController::class, 'passwordRecover'])->name('password.recover');

// Catálogo y Chatbot (Uso general)
Route::get('/catalogo', [ProductoCatalogoController::class, 'index'])->name('catalogo.index');
Route::get('/chatbot', function () { return view('chatbot.ui'); })->name('chatbot.index');
Route::post('/chatbot/webhook', [ChatbotWebhookController::class, 'handle'])->name('chatbot.webhook');

/*
|--------------------------------------------------------------------------
| Rutas Protegidas: CLIENTES (Requiere Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    
    Route::get('/home', function () {
        return view('index'); 
    })->name('home');

    Route::get('/chat/espera/{id}', [ChatVentasController::class, 'vistaEsperaCliente'])->name('chat.espera');
    Route::post('/chat/enviar-cliente/{id}', [ChatVentasController::class, 'enviarMensajeCliente'])->name('chat.enviar.cliente');
    Route::get('/chat/mensajes-cliente/{id}', [ChatVentasController::class, 'obtenerMensajesCliente'])->name('chat.mensajes.cliente');

    // Gestión de Carrito y Compras
    Route::prefix('cliente')->name('cliente.')->group(function () {
        Route::get('/catalogo', function () {
            $productos = \App\Models\Producto::where('cantidad', '>', 0)->orderBy('nombre')->get();
            return view('cliente.catalogo-cliente', compact('productos'));
        })->name('catalogo');

        Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito');
        Route::post('/carrito/{id}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
        Route::delete('/carrito/{id}', [CarritoController::class, 'quitar'])->name('carrito.quitar');
        Route::delete('/carrito-vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

        Route::get('/checkout', [CheckoutController::class, 'formulario'])->name('checkout');
        Route::post('/checkout/pagar', [CheckoutController::class, 'procesarPago'])->name('checkout.pagar');
        Route::post('/checkout/confirmar', [CheckoutController::class, 'confirmarPago'])->name('checkout.confirmar');
    });

    // Cotizaciones del Usuario
    Route::get('/mis-cotizaciones', function () { return view('cotizaciones.historial'); });
    Route::get('/cotizaciones/historial', [CotizacionController::class, 'historial']);
    Route::get('/cotizaciones/{id}', [CotizacionController::class, 'show']);
    Route::get('/cotizaciones/{id}/pdf', [CotizacionController::class, 'generarPDF']);

    Route::get('/cotizaciones/{id}/pdf', [CotizacionController::class, 'generarPDF']);
    Route::get('/cotizaciones/{id}/excel', [CotizacionController::class, 'generarExcel']);
    Route::post('/cotizaciones/{id}/enviar-correo', [CotizacionController::class, 'enviarCorreo'])
     ->name('cotizaciones.enviarCorreo');
     
     // --- Sistema de Notificaciones e Interacción Just In Time ---
    Route::post('/notificaciones/{id}/leer', [ChatVentasController::class, 'leerNotificacion'])->name('notificaciones.leer');
    
    // Ruta de destino del cliente tras confirmar su pedido (Historial de compras/pagos)
    Route::get('/mis-pedidos', function () { 
        return view('cliente.pedidos'); // O la vista donde proceses tus QR y pagos
    })->name('pedidos.index');

});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas: PERSONAL DE VENTAS (Configuración de Pruebas en Vivo)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('ventas')->name('ventas.')->group(function () {

    Route::get('/', [ChatVentasController::class, 'dashboardPrincipal'])->name('dashboard');
    
    Route::prefix('chat')->name('chat.')->group(function () {
        
        Route::get('/bandeja', [ChatVentasController::class, 'bandeja'])->name('bandeja');
        Route::get('/derivaciones', [ChatVentasController::class, 'obtenerDerivaciones'])->name('derivaciones');
        Route::get('/atender/{id}', [ChatVentasController::class, 'vistaAtenderAgente'])->name('atender');
        Route::post('/enviar-agente/{id}', [ChatVentasController::class, 'enviarMensajeAgente'])->name('enviar.agente');
        Route::get('/mensajes-agente/{id}', [ChatVentasController::class, 'obtenerMensajesAgente'])->name('mensajes.agente');
        
        Route::post('/finalizar-agente/{id}', [ChatVentasController::class, 'finalizarChatAgente'])->name('finalizar');
    });

   Route::prefix('cotizaciones')->name('cotizaciones.')->group(function () {
        
        Route::get('/', [CotizacionController::class, 'indexVentas'])->name('index');

        Route::get('/data', [CotizacionController::class, 'dataVentas'])->name('data');

        Route::post('/{id}/actualizar-estado', [CotizacionController::class, 'actualizarEstadoVentas'])->name('actualizarEstado');
        Route::get('/{id}/detalle', [CotizacionController::class, 'obtenerDetalleVentas'])->name('detalle');

        // NUEVAS RUTAS DE CONTROL DE NOTIFICACIONES
        Route::post('/{id_cotizacion}/aprobar', [ChatVentasController::class, 'aprobarCotizacion'])->name('aprobar');
        Route::post('/{id_cotizacion}/rechazar', [ChatVentasController::class, 'rechazarCotizacion'])->name('rechazar');
    });
});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas: ADMINISTRADORES (Login + Rol Admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'es_admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Principal
    Route::get('/', function () {
        return view('admin-dashboard');
    })->name('dashboard');

    // Gestión de FAQ
    Route::resource('faq', FaqController::class)->except(['create', 'show', 'edit']);

    // Gestión de Productos
    Route::prefix('productos')->name('productos.')->group(function () {
        Route::get('/', [ProductoController::class, 'index'])->name('index');
        Route::get('/crear', [ProductoController::class, 'create'])->name('crear');
        Route::post('/', [ProductoController::class, 'store'])->name('store');
        Route::get('/{id}/editar', [ProductoController::class, 'edit'])->name('editar');
        Route::put('/{id}', [ProductoController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductoController::class, 'destroy'])->name('destroy');
    });

    // Gestión de Ventas
    Route::prefix('ventas')->name('ventas.')->group(function () {
        Route::get('/', [VentaController::class, 'index'])->name('index');
        Route::post('/', [VentaController::class, 'store'])->name('store');
    });

    // Bandeja de Pedidos (Inbox)
    Route::prefix('pedidos')->name('pedidos.')->group(function () {
        Route::get('/', [BandejaController::class, 'index'])->name('bandeja');
        Route::post('/{id}/aceptar', [BandejaController::class, 'aceptar'])->name('aceptar');
        Route::post('/{id}/rechazar', [BandejaController::class, 'rechazar'])->name('rechazar');
        Route::post('/cotizacion/{id}/completar', [BandejaController::class, 'completar'])->name('completar');
    });

    // Reportes Avanzados
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', function () { return view('Admin.reportes.general'); })->name('index');
        Route::get('/general', function () { return view('Admin.reportes.general'); })->name('general');
        Route::get('/por-fecha', function () { return view('Admin.reportes.por_fecha'); })->name('porFecha');
        Route::get('/filtrado', function () { return view('Admin.reportes.filtrado'); })->name('filtros');
        
        // Exportaciones y API
        Route::get('/exportar/excel', [ReporteController::class, 'exportarExcel'])->name('exportar.excel');
        Route::get('/exportar/pdf', [ReporteController::class, 'exportarPdf'])->name('exportar.pdf');
        Route::get('/usuarios', [ReporteController::class, 'getUsuarios']);
        Route::get('/filtrado-data', [ReporteController::class, 'reporteFiltrado']);
        Route::get('/metricas', [ReporteController::class, 'metricas']);
    });
    Route::post('/enviar-reporte', [ReporteController::class, 'enviarReportePorCorreo'])
    ->name('enviar.reporte');

    // Gestión de usuarios
        Route::get('/usuarios', [App\Http\Controllers\Admin\UsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios/{id}/rol', [App\Http\Controllers\Admin\UsuarioController::class, 'updateRol'])->name('usuarios.updateRol');
        Route::get('/usuarios/{id}/toggle', [App\Http\Controllers\Admin\UsuarioController::class, 'toggleActivo'])->name('usuarios.toggleActivo');

});