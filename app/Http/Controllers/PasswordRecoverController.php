<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordRecoverController extends Controller
{
    public function passwordRecover(Request $request)
    {
        $request->validate([
            'correo' => 'required|email|exists:usuarios,correo', 
            'respuesta_secreta' => 'required|string|max:255',
            'password' => 'required|string|min:8' 
        ]);

        $usuario = Usuario::where('correo', $request->correo)
                          ->where('respuesta_secreta', $request->respuesta_secreta)
                          ->first();

        if ($usuario) {
            $usuario->password_hash = Hash::make($request->password);
            $usuario->save();

            return redirect()->route('login')->with('success', 'Contraseña actualizada correctamente.');
        }

        return back()->withErrors([
            'respuesta_secreta' => 'La respuesta secreta o el correo no coinciden con nuestros registros.'
        ])->withInput();
    }
}