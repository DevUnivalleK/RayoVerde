<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/envios', fn() => view('Envios.envios-dashboard'))->name('admin.envios.index');
Route::get('/admin/envios/gestionar', fn() => view('Envios.envios-gestionar'))->name('admin.envios.gestionar');
Route::get('/admin/envios/crear', fn() => view('Envios.envios-form'))->name('admin.envios.crear');
Route::get('/admin/envios/editar', fn() => view('Envios.envios-form'))->name('admin.envios.editar');


Route::get('/admin/faqs', fn() => view('FAQS.faqs'))->name('admin.faqs');
Route::get('/admin/faqs/gestionar', fn() => view('FAQS.faqs-gestionar'))->name('admin.faqs.gestionar');
Route::get('/admin/faqs/crear', fn() => view('FAQS.faqs-form'))->name('admin.faqs.crear');
Route::get('/admin/faqs/editar', fn() => view('FAQS.faqs-form'))->name('admin.faqs.editar');


Route::get('/admin/regiones/gestionar', fn() => view('Regiones.regiones-gestionar'))->name('admin.regiones.gestionar');
Route::get('/admin/regiones/crear', fn() => view('Regiones.regiones-form'))->name('admin.regiones.crear');
Route::get('/admin/regiones/editar', fn() => view('Regiones.regiones-form'))->name('admin.regiones.editar');