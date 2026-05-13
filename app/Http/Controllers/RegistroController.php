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
        'nombre' => ['required', 'string', 'max:255', 'regex:/^\S+(?: \S+)?$/'],
        'apellido' => ['required', 'string', 'max:255', 'regex:/^\S+(?: \S+)?$/'],
        'correo' => 'required|string|email|max:255|unique:usuarios,correo',
        'password' => 'required|string|min:8|confirmed',
        'respuesta_secreta' => 'required|string|max:255',
        'empresa' => 'nullable|string|max:255',
        'telefono' => 'nullable|numeric|digits_between:7,15',
        'direccion' => 'nullable|string|max:255'
    ], [
        'nombre.regex' => 'El nombre solo puede contener un espacio y no debe tener espacios al inicio o final.',
        'apellido.regex' => 'El apellido solo puede contener un espacio y no debe tener espacios al inicio o final.',
        'telefono.numeric' => 'El campo teléfono solo debe contener números.',
        'telefono.digits_between' => 'El teléfono debe tener entre 7 y 15 dígitos.',
        'required' => 'El campo :attribute es obligatorio.',
        'email' => 'El formato del correo no es válido.',
        'unique' => 'Este correo ya está registrado.',
        'confirmed' => 'La confirmación de la contraseña no coincide.',
        'password.min' => 'La contraseña debe tener al menos :min caracteres.',
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

                $user->roles()->attach(1);

                return $user;
            });

            Auth::login($usuario);
            
            return redirect()->route('home')->with('success', 'Cuenta creada con éxito.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al registrar: ' . $e->getMessage()])->withInput();
        }
    }
}