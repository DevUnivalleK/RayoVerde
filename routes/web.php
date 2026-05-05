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
