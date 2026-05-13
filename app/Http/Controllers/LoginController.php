<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        $key = 'login-attempt:' . $request->ip() . $request->correo;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', "Demasiados intentos. Intenta en $seconds segundos.");
        }

        if (Auth::attempt(['correo' => $request->correo, 'password' => $request->password], $request->filled('remember'))) {
            
            $request->session()->regenerate();
            RateLimiter::clear($key);

            return redirect()->intended(route('home'))->with('success', 'Bienvenido de nuevo.');
        }

        RateLimiter::hit($key, 60);

        return back()->withErrors([
            'correo' => 'El correo eléctronico o la contraseña son incorrectos.',
        ])->onlyInput('correo');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}