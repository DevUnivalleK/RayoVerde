<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LoginController;

// --- Rutas de Inicio ---
Route::get('/', function () {
    return view('index'); // Se mantiene la vista index como principal
})->name('home');

// --- Autenticación (Login) ---
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --- Autenticación (Registro) ---
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



Route::get('/chatbot', function () {
    return view('chatbot.index'); 
})->name('chatbot.ui');

Route::get('/chatbot', function () {
    return view('chatbot.ui'); 
})->name('chatbot.index');


// --- Administración de FAQ (Grupo con Prefijo) ---
Route::prefix('admin')->group(function () {
    Route::get('/faq', [FaqController::class, 'index'])->name('admin.faq.index');
    Route::post('/faq', [FaqController::class, 'store'])->name('admin.faq.store');
    Route::put('/faq/{id}', [FaqController::class, 'update'])->name('admin.faq.update');
    Route::delete('/faq/{id}', [FaqController::class, 'destroy'])->name('admin.faq.destroy');
});