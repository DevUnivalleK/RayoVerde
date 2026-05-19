@extends('PersonalVentas.app')

@section('title', 'Dashboard Ventas')
@section('breadcrumb', 'Resumen General')

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/estilosDash.css') }}">
@endpush

@section('content')

{{-- ── HERO DE VENTAS DINÁMICO ─────────────────────────── --}}
<div class="rv-hero">
    <div class="rv-hero-body">
        <div class="rv-hero-eyebrow font-semibold">— Panel Personal</div>
        <h1>Módulo de <em class="italic text-green-300">Ventas</em></h1>
        <p>Atención de requerimientos del bot y gestión de cotizaciones de lubricantes</p>
    </div>
    <div class="rv-stats">
        <div class="rv-stat">
            <div class="rv-stat-val">{{ $chatsEnEspera }}</div>
            <div class="rv-stat-lbl">Chats en Espera</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">{{ $cotizacionesActivas }}</div>
            <div class="rv-stat-lbl">Cotizaciones Activas</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">{{ $atendidosHoy }}</div>
            <div class="rv-stat-lbl">Atendidos Hoy</div>
        </div>
    </div>
</div>

{{-- ── VISTA RÁPIDA DE TRABAJO PENDIENTE REAL ──────────── --}}
<div class="rv-card mt-6">
    <div class="rv-card-head">
        <div class="rv-card-title">
            <div class="rv-card-icon"><img src="/images/chat.png" alt="Chat"></div>
            Solicitudes de Chat Pendientes (Derivaciones Asistente)
        </div>
        <a href="{{ route('ventas.chat.bandeja') }}" class="btn btn-dark">
            Ir a la Bandeja
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Correo</th>
                <th>Último Paso Bot</th>
                <th>Hora de Solicitud</th>
                <th style="text-align:center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($chatsPendientes as $chat)
                <tr>
                    <td style="font-weight:700;">{{ $chat->cliente_nombre }}</td>
                    <td class="text-gray-600 text-xs font-mono">{{ $chat->cliente_correo }}</td>
                    <td>
                        Derivado desde: <span class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">{{ $chat->paso_actual }}</span>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($chat->iniciada_en)->locale('es')->diffForHumans() }}
                    </td>
                    <td>
                        <div class="td-actions" style="justify-content:center;">
                            <a href="{{ route('ventas.chat.atender', $chat->id_conversacion) }}" class="btn btn-dark" style="padding: 5px 10px; font-size: 0.8rem;">
    Atender Chat
</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-500">
                         ¡Excelente trabajo! No hay solicitudes de chat pendientes en este momento.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection