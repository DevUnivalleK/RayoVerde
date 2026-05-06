<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/registro', function () {
    return view('auth.register');
})->name('registro');

Route::post('/registro', [RegistroController::class, 'registrar'])->name('registrar.post');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/dashboard', function () {
    if (!session()->has('usuario_id')) {
        return redirect()->route('login');
    }
    return "Bienvenido " . session('usuario_nombre');
})->name('dashboard');