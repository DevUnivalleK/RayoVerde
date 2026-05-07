<?php

use Illuminate\Support\Facades\Route;

// Controladores de Autenticación y FAQ
use App\Http\Controllers\FaqController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LoginController;

// Controladores de Productos y Ventas (Trabajo de Tapia/Challapa)
use App\Http\Controllers\ProductoCatalogoController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\VentaController;

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
    Route::get('/{id}/editar',  [ProductoController::class, 'edit'])->name('edit');
    Route::put('/{id}',         [ProductoController::class, 'update'])->name('update');
    Route::delete('/{id}',      [ProductoController::class, 'destroy'])->name('destroy');
});

// Gestión de Ventas
Route::prefix('admin/ventas')->name('admin.ventas.')->group(function () {
    Route::get('/', [VentaController::class, 'index'])->name('index');
    Route::post('/', [VentaController::class, 'store'])->name('store');
});