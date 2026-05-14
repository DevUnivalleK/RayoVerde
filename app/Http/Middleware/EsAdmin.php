<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->roles->contains('id_rol', 2)) {
            return $next($request);
        }

        return redirect('/home')->with('error', 'No tienes permisos para acceder a esta sección.');
    }
}
