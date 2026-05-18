@extends('layouts.admin-sidebar')

@section('title', 'Gestión de Usuarios')
@section('breadcrumb', 'Configuración / Usuarios')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-green-700 mb-6">Gestión de Usuarios</h1>
    
    <!-- Tabla de usuarios -->
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Correo</th>
                    <th class="px-4 py-3">Rol Actual</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($usuarios as $usuario)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm">{{ $usuario->id_usuario }}</td>
                    <td class="px-4 py-3 text-sm">{{ $usuario->nombre }} {{ $usuario->apellido }}</td>
                    <td class="px-4 py-3 text-sm">{{ $usuario->correo }}</td>
                    <td class="px-4 py-3 text-sm">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($usuario->roles->first()?->nombre == 'Administrador') bg-green-100 text-green-800
                            @elseif($usuario->roles->first()?->nombre == 'Personal_Ventas') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $usuario->roles->first()?->nombre ?? 'Cliente' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <span class="px-2 py-1 text-xs rounded-full {{ $usuario->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm space-x-2">
                        <!-- Botón cambiar rol -->
                        <form action="{{ route('admin.usuarios.updateRol', $usuario->id_usuario) }}" method="POST" class="inline">
                            @csrf
                            <select name="id_rol" class="border rounded px-2 py-1 text-xs">
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->id_rol }}" 
                                        {{ $usuario->roles->first()?->id_rol == $rol->id_rol ? 'selected' : '' }}>
                                        {{ $rol->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded text-xs hover:bg-blue-700">
                                Cambiar
                            </button>
                        </form>
                        
                        <!-- Botón activar/desactivar -->
                        <a href="{{ route('admin.usuarios.toggleActivo', $usuario->id_usuario) }}" 
                           class="px-2 py-1 rounded text-xs {{ $usuario->activo ? 'bg-yellow-600 text-white hover:bg-yellow-700' : 'bg-green-600 text-white hover:bg-green-700' }}">
                            {{ $usuario->activo ? 'Desactivar' : 'Activar' }}
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection