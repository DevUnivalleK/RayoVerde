<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*Route::bind('producto', function ($value) {
            return Producto::where('id_producto', $value)->firstOrFail();
        });*/

        // --- COMPOSER OPTIMIZADO PARA EL SIDEBAR DEL CLIENTE ---
        View::composer('layouts.user-sidebar', function ($view) {
            if (Auth::check()) {
                $id_usuario = Auth::id();

                $cliente = DB::table('clientes')->where('id_usuario', $id_usuario)->first();

                if ($cliente) {
                    // 1. Jalamos las últimas 5 notificaciones ordenadas por fecha de envío
                    $notificaciones = DB::table('notificaciones')
                        ->where('id_cliente', $cliente->id_cliente)
                        ->orderBy('enviada_en', 'desc')
                        ->take(5)
                        ->get();

                    // 2. Contamos cuántas de esas notificaciones siguen sin leerse (leida = false)
                    $conteoSinLeer = DB::table('notificaciones')
                        ->where('id_cliente', $cliente->id_cliente)
                        ->where('leida', false)
                        ->count();

                    // Compartimos las variables únicamente con el sidebar
                    $view->with(compact('notificaciones', 'conteoSinLeer'));
                }
            }
        });
    }
}