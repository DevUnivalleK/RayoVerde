<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        $key = 'login-attempt:' . $request->ip() . $request->correo;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', "Demasiados intentos. Intenta de nuevo en $seconds segundos.");
        }

        $usuario = Usuario::where('correo', $request->correo)->first();

        if ($usuario && Hash::check($request->password, $usuario->password_hash)) {
            //if ($usuario && Hash::check($request->password, $usuario->password_hash)) {
            
            RateLimiter::clear($key);

            Session::put('usuario_id', $usuario->id_usuario);
            Session::put('usuario_nombre', $usuario->nombre);
            Session::put('usuario_rol', $usuario->rol);

            return redirect()->route('home')->with('success', 'Bienvenido al sistema');

        }

        RateLimiter::hit($key, 60);

        return back()->withErrors([
            'login_error' => 'El correo o la contraseña son incorrectos.',
        ])->withInput();
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }
}