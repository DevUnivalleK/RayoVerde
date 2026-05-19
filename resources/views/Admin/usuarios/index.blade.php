@extends('layouts.admin-sidebar')

@section('title', 'Gestión de Usuarios')
@section('breadcrumb', 'Configuración / Usuarios')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/estilosDash.css') }}">
@endpush

@section('content')

{{-- ── HERO ──────────────────────────────────────────────────── --}}
<div class="rv-hero">
    <div class="rv-hero-body">
        <h1>Gestión de <em>Usuarios</em></h1>
        <p>Administra roles y estado de acceso del sistema</p>
    </div>

    <div class="rv-stats">
        <div class="rv-stat">
            <div class="rv-stat-val">{{ $usuarios->count() }}</div>
            <div class="rv-stat-lbl">Usuarios registrados</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val"><span class="dot dot-on"></span>{{ $usuarios->where('activo', true)->count() }}</div>
            <div class="rv-stat-lbl">Usuarios activos</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val"><span class="dot dot-off"></span>{{ $usuarios->where('activo', false)->count() }}</div>
            <div class="rv-stat-lbl">Inactivos</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">{{ $roles->count() }}</div>
            <div class="rv-stat-lbl">Roles disponibles</div>
        </div>
    </div>
</div>

{{-- ── Flash messages ──────────────────────────────────────── --}}
@if(session('success'))
    <div class="rv-flash rv-flash-success">✓ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="rv-flash rv-flash-error">✗ {{ session('error') }}</div>
@endif

{{-- ── TABLA ────────────────────────────────────────────────── --}}
<div class="rv-card">
    <div class="rv-card-head">
        <div class="rv-card-title">
            Usuarios registrados
        </div>
        <span class="rv-badge">{{ $usuarios->count() }} registros</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol Actual</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->id_usuario }}</td>
                <td><strong>{{ $usuario->nombre }} {{ $usuario->apellido }}</strong></td>
                <td>{{ $usuario->correo }}</td>
                <td>
                    <span class="tipo-tag">
                        {{ $usuario->roles->first()?->nombre ?? 'Cliente' }}
                    </span>
                </td>
                <td>
                    <span class="pill {{ $usuario->activo ? 'pill-on' : 'pill-off' }}">
                        {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td>
                    <div class="td-actions">
                        {{-- Cambiar rol --}}
                        <form action="{{ route('admin.usuarios.updateRol', $usuario->id_usuario) }}"
                              method="POST" style="display:flex; align-items:center; gap:6px;">
                            @csrf
                            <select name="id_rol" class="rv-select">
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->id_rol }}"
                                        {{ $usuario->roles->first()?->id_rol == $rol->id_rol ? 'selected' : '' }}>
                                        {{ $rol->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-blue" style="padding: 7px 12px;">
                                Cambiar
                            </button>
                        </form>

                        {{-- Activar / Desactivar --}}
                        <a href="{{ route('admin.usuarios.toggleActivo', $usuario->id_usuario) }}"
                           class="btn {{ $usuario->activo ? 'btn-warning' : 'btn-dark' }}"
                           style="padding: 7px 12px;">
                            {{ $usuario->activo ? 'Desactivar' : 'Activar' }}
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection