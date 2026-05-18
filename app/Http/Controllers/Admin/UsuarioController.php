<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    // Lista de usuarios
    public function index()
    {
        $usuarios = Usuario::with('roles')->get();
        $roles = Rol::all();
        return view('admin.usuarios.index', compact('usuarios', 'roles'));
    }
    
    // Actualizar rol de usuario
        public function updateRol(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        
        // Sincronizar roles (relación muchos a muchos)
        $usuario->roles()->sync([$request->id_rol]);
        
        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Rol actualizado correctamente');
    }
        
    // Alternar activo/inactivo
    public function toggleActivo($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->activo = !$usuario->activo;
        $usuario->save();
        
        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Estado del usuario actualizado');
    }
}