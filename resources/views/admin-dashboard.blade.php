@extends('layouts.admin-sidebar')

@section('title', 'Dashboard')
@section('breadcrumb', 'Resumen General')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/estilosDash.css') }}">
@endpush

@section('content')

{{-- ── HERO ─────────────────────────────────────────── --}}
<div class="rv-hero">
    <div class="rv-hero-body">
        <div class="rv-hero-eyebrow">Panel</div>
        <h1>Resumen <em>General</em></h1>
        <p>Bienvenido al control de mando de Rayo Verde</p>
    </div>
    <div class="rv-stats">
        <div class="rv-stat">
            <div class="rv-stat-val">1,250</div>
            <div class="rv-stat-lbl">Ventas hoy</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">14</div>
            <div class="rv-stat-lbl">Pedidos</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">5</div>
            <div class="rv-stat-lbl">Pendientes</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">82</div>
            <div class="rv-stat-lbl">Usuarios</div>
        </div>
    </div>
</div>

{{-- ── TABLA DE TRANSACCIONES RECIENTES ────────────── --}}
<div class="rv-card">
    <div class="rv-card-head">
        <div class="rv-card-title">
            <div class="rv-card-icon"><img src="/images/icono-ventas.png" alt=""></div>
            Transacciones Recientes
        </div>
        <a href="{{ route('admin.reportes.index') }}" class="btn btn-dark">
            Descargar Reporte
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Estado</th>
                <th style="text-align:center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight:700;">#RV-552</td>
                <td>Maria Lopez</td>
                <td>Botellón 20L</td>
                <td class="price">120 BOB</td>
                <td><span class="pill pill-on">Completado</span></td>
                <td>
                    <div class="td-actions" style="justify-content:center;">
                        <button class="btn-icon btn-icon-edit">
                            <img src="/images/visibility.png" alt="Ver">
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="font-weight:700;">#RV-553</td>
                <td>Carlos Ruiz</td>
                <td>Pack Familiar</td>
                <td class="price">85 BOB</td>
                <td><span class="rv-badge-espera">En camino</span></td>
                <td>
                    <div class="td-actions" style="justify-content:center;">
                        <button class="btn-icon btn-icon-edit">
                            <img src="/images/visibility.png" alt="Ver">
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@endsection