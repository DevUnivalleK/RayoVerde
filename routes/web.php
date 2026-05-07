<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Página principal
Route::get('/', function () {
    return view('index');
});

// Dashboard Admin
Route::get('/admin', function () {
    return view('admin-dashboard');
});

// Login
Route::get('/login', function () {
    return view('login');
});

// Registro
Route::get('/registro', function () {
    return view('registro');
});

Route::get('/chatbot', function () {
    return view('chatbot.index'); 
})->name('chatbot.ui');

Route::get('/chatbot', function () {
    return view('chatbot.ui'); 
})->name('chatbot.index');

use App\Http\Controllers\FaqController;

Route::prefix('admin')->group(function () {
    Route::get('/faq', [FaqController::class, 'index'])->name('admin.faq.index');
    Route::post('/faq', [FaqController::class, 'store'])->name('admin.faq.store');
    Route::put('/faq/{id}', [FaqController::class, 'update'])->name('admin.faq.update');
    Route::delete('/faq/{id}', [FaqController::class, 'destroy'])->name('admin.faq.destroy');
});