<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Cliente; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB; 

class RegistroController extends Controller
{
    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:usuarios,correo',
            'password' => 'required|string|min:8|confirmed',
            'respuesta_secreta' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255'
        ]);

        try {
            $usuario = DB::transaction(function () use ($request) {
                $user = Usuario::create([
                    'nombre'            => $request->nombre,
                    'apellido'          => $request->apellido,
                    'correo'            => $request->correo,
                    'password_hash'     => Hash::make($request->password), 
                    'activo'            => true,
                    'respuesta_secreta' => $request->respuesta_secreta    
                ]);

                $user->cliente()->create([
                    'empresa'   => $request->empresa ?? 'Particular',
                    'telefono'  => $request->telefono,
                    'direccion' => $request->direccion
                ]);

                return $user;
            });

            Auth::login($usuario);
            
            return redirect()->route('home')->with('success', 'Cuenta creada con éxito.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al registrar: ' . $e->getMessage()])->withInput();
        }
    }
}