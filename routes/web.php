<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductoCatalogoController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\VentaController;

Route::get('/', function () {
    return view('welcome');
});

// Catálogo público
Route::get('/catalogo', [ProductoCatalogoController::class, 'index'])->name('catalogo.index');

// Admin productos (CRUD completo)
Route::prefix('admin/productos')->name('admin.productos.')->group(function () {
    Route::get('/',          [ProductoController::class, 'index'])->name('index');
    Route::get('/crear',     [ProductoController::class, 'create'])->name('crear');
    Route::post('/',         [ProductoController::class, 'store'])->name('store');
    Route::get('/{id}/editar',  [ProductoController::class, 'edit'])->name('editar');
    Route::put('/{id}',         [ProductoController::class, 'update'])->name('update');
    Route::delete('/{id}',      [ProductoController::class, 'destroy'])->name('destroy');
});

// Admin ventas
Route::prefix('admin/ventas')->name('admin.ventas.')->group(function () {
    Route::get('/', [VentaController::class, 'index'])->name('index');
    Route::post('/', [VentaController::class, 'store'])->name('store');
});