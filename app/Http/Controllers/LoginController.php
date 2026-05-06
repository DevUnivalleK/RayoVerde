<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('correo', $request->correo)->first();

        if ($usuario && Hash::check($request->password, $usuario->password_hash)) {
            
            Session::put('usuario_id', $usuario->id_usuario);
            Session::put('usuario_nombre', $usuario->nombre);
            Session::put('usuario_rol', $usuario->rol);

            return redirect()->route('dashboard')->with('success', 'Bienvenido al sistema');
        }

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