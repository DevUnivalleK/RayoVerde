<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 

class RegistroController extends Controller
{
    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:usuarios,correo',
            'password' => 'required|string|min:8|confirmed',
            'respuesta_secreta' => 'required|string|max:255'
        ]);

        $usuario = Usuario::create([
            'nombre'            => $request->nombre,
            'apellido'          => $request->apellido,
            'correo'            => $request->correo,
            'password_hash'     => Hash::make($request->password), 
            'activo'            => true,
            'respuesta_secreta' => $request->respuesta_secreta    
        ]);

        Auth::login($usuario);

        return redirect()->route('dashboard')->with('success', 'Bienvenido, cuenta creada.');
    }
}