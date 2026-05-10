<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class PasswordRecoverController extends Controller
{
    //
    public function passwordRecover(Request $request){
        $request->validate([
            'respuesta_secreta' => 'required|max:255',
            'password' => 'required'
        ]);

        $respuesta = Usuario::where('respuesta_secreta', $request->respuesta_secreta)->first();

        if($respuesta){
            $respuesta->password_hash = Hash::make($request->password);
            //Hash::make($request->password);
            $respuesta->save();
            return redirect()->route('login')->with('success', 'Bienvenido al sistema');
        }
        else{
            echo "No se pudo realizar el cambio";
        }

    }
}
