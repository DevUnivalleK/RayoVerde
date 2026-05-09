<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistroController extends Controller
{
    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:usuarios,correo',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Usuario::create([
            'nombre'        => $request->nombre,
            'apellido'      => $request->apellido,
            'correo'        => $request->correo,
            'password_hash' => Hash::make($request->password), 
           // 'rol'           => 'cliente', 
            'activo'        => true,     
        ]);

        return redirect()->route('login')->with('success', 'Cuenta creada con éxito.');
    }
}