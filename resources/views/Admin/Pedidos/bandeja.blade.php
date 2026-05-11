@extends('layout')
@section('title', 'Bandeja de Pedidos — Rayo Verde')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --green-900: #1a3d06;
    --green-700: #2c5a0e;
    --green-600: #3b6d11;
    --green-500: #4f9020;
    --green-400: #6bb83a;
    --green-100: #edf5e1;
    --green-50:  #f4faea;
    --amber-100: #fdf0db;
    --amber-700: #633806;
    --amber-500: #c47c1a;
    --ink:       #0d1f05;
    --ink-mid:   #3a4a30;
    --ink-muted: #7a8f6e;
    --border:    #dde8d0;
    --surface:   #f8faf4;
    --white:     #ffffff;
    --radius-sm: 8px;
    --radius-md: 14px;
    --radius-lg: 20px;
    --shadow-card: 0 2px 16px rgba(59,109,17,0.08), 0 1px 3px rgba(0,0,0,0.04);
    --shadow-hero: 0 8px 40px rgba(26,61,6,0.22);
}

body { font-family: 'Sora', sans-serif; background: var(--surface); color: var(--ink); line-height: 1.5; }

/* Hero */
.rv-hero {
    position: relative;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 60%, var(--green-600) 100%);
    border-radius: var(--radius-lg); padding: 32px 36px 0;
    margin-bottom: 20px; overflow: hidden; box-shadow: var(--shadow-hero);
}
.rv-hero::before {
    content: ''; position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 60% 80% at 90% -10%, rgba(107,184,58,0.18) 0%, transparent 60%),
        radial-gradient(ellipse 40% 60% at -5% 100%, rgba(255,255,255,0.05) 0%, transparent 50%);
    pointer-events: none;
}
.rv-hero::after {
    content: ''; position: absolute; right: -60px; bottom: -60px;
    width: 260px; height: 260px; border-radius: 50%;
    border: 40px solid rgba(255,255,255,0.04);
}
.rv-hero-top { display: flex; align-items: center; gap: 14px; margin-bottom: 24px; position: relative; }
.rv-logo {
    width: 48px; height: 48px; border-radius: 12px;
    background: rgba(255,255,255,0.12); border: 1.5px solid rgba(255,255,255,0.25);
    display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;
}
.rv-logo img { width: 100%; height: 100%; object-fit: contain; }
.rv-brand-name { font-family: 'Instrument Serif', serif; font-size: 20px; color: #fff; }
.rv-brand-sub { font-size: 10px; color: rgba(255,255,255,0.45); letter-spacing: 2px; text-transform: uppercase; margin-top: 2px; }
.rv-hero-body { position: relative; margin-bottom: 32px; }
.rv-hero-eyebrow {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 10px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--green-400); margin-bottom: 10px;
}
.rv-hero-eyebrow::before { content: ''; display: block; width: 20px; height: 1.5px; background: var(--green-400); }
.rv-hero h1 {
    font-family: 'Instrument Serif', serif; font-size: 38px; color: #fff;
    font-weight: 400; letter-spacing: -1px; line-height: 1.1; margin-bottom: 8px;
}
.rv-hero h1 em { font-style: italic; color: var(--green-400); }
.rv-hero p { color: rgba(255,255,255,0.55); font-size: 13px; font-weight: 300; }

/* Stats */
.rv-stats { display: grid; grid-template-columns: repeat(3, 1fr); border-top: 1px solid rgba(255,255,255,0.1); }
.rv-stat { padding: 20px 24px; border-right: 1px solid rgba(255,255,255,0.08); transition: background .2s; }
.rv-stat:last-child { border-right: none; }
.rv-stat:hover { background: rgba(255,255,255,0.05); }
.rv-stat-val { font-family: 'Instrument Serif', serif; font-size: 30px; color: #fff; letter-spacing: -1px; line-height: 1; display: flex; align-items: center; gap: 8px; }
.rv-stat-lbl { font-size: 10px; color: rgba(255,255,255,0.4); margin-top: 5px; letter-spacing: 1px; text-transform: uppercase; }
.dot-amber { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #f0a030; box-shadow: 0 0 6px rgba(240,160,48,0.6); animation: pulseDot 1.4s ease-in-out infinite; }
.dot-ok    { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #8de88d; box-shadow: 0 0 6px rgba(141,232,141,0.6); }
.dot-off   { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #ff9e9e; }
@keyframes pulseDot { 0%,100% { opacity:1; transform:scale(1); } 50% { opacity:.5; transform:scale(1.3); } }

/* Flash */
.rv-flash {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 18px; border-radius: var(--radius-sm);
    font-size: 13px; font-weight: 500; margin-bottom: 16px;
}
.rv-flash-success { background: var(--green-100); color: var(--green-700); border: 1px solid #b8d9a0; }
.rv-flash-error   { background: #fceaea; color: #7a1f1f; border: 1px solid #f5c2c2; }

/* Empty state */
.rv-empty-bandeja {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius-md); box-shadow: var(--shadow-card);
    padding: 60px 20px; text-align: center; color: var(--ink-muted);
}
.rv-empty-bandeja img { width: 52px; height: 52px; object-fit: contain; opacity: 0.2; margin: 0 auto 16px; display: block; }
.rv-empty-bandeja p { font-size: 14px; }

/* Cards de pedido */
.rv-pedido-card {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius-md); overflow: hidden;
    box-shadow: var(--shadow-card); margin-bottom: 16px;
    transition: box-shadow .2s;
}
.rv-pedido-card:hover { box-shadow: 0 6px 28px rgba(59,109,17,0.12); }

/* Header del pedido */
.rv-pedido-head {
    padding: 16px 20px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    background: var(--white); flex-wrap: wrap; gap: 10px;
}
.rv-pedido-head-left { display: flex; align-items: center; gap: 12px; }

.rv-pedido-codigo {
    font-size: 14px; font-weight: 800; color: var(--green-700);
    letter-spacing: 1px; font-variant-numeric: tabular-nums;
}
.rv-pedido-hora {
    font-size: 11px; color: var(--ink-muted); font-weight: 500; margin-top: 2px;
}
.rv-badge-espera {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px;
    background: var(--amber-100); color: var(--amber-700);
    font-size: 11px; font-weight: 700; letter-spacing: 0.3px;
}
.rv-badge-espera::before {
    content: ''; width: 6px; height: 6px; border-radius: 50%;
    background: var(--amber-500); animation: pulseDot 1.4s infinite;
}

/* Cuerpo del pedido — 3 columnas */
.rv-pedido-body {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 0;
}
@media (max-width: 700px) { .rv-pedido-body { grid-template-columns: 1fr; } }

.rv-pedido-section {
    padding: 16px 20px;
    border-right: 1px solid #f0f4eb;
}
.rv-pedido-section:last-child { border-right: none; }

.rv-section-title {
    font-size: 10px; font-weight: 700; color: var(--ink-muted);
    letter-spacing: 1.2px; text-transform: uppercase; margin-bottom: 12px;
    display: flex; align-items: center; gap: 6px;
}
.rv-section-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }

/* Cliente info */
.rv-cliente-nombre { font-size: 14px; font-weight: 700; color: var(--ink); margin-bottom: 4px; }
.rv-cliente-detalle { font-size: 12px; color: var(--ink-muted); margin-bottom: 2px; }
.rv-cliente-detalle strong { color: var(--ink-mid); font-weight: 600; }

/* Productos lista */
.rv-producto-item {
    display: flex; align-items: center; gap: 10px;
    padding: 6px 0; border-bottom: 1px solid #f5f8f0;
}
.rv-producto-item:last-child { border-bottom: none; }
.rv-producto-thumb {
    width: 36px; height: 36px; border-radius: 6px;
    object-fit: cover; border: 1px solid var(--border); flex-shrink: 0;
    background: var(--green-50);
}
.rv-producto-thumb-ph {
    width: 36px; height: 36px; border-radius: 6px;
    background: var(--green-50); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.rv-producto-thumb-ph img { width: 16px; height: 16px; object-fit: contain; opacity: 0.3; }
.rv-producto-nombre { font-size: 12px; font-weight: 600; color: var(--ink); flex: 1; }
.rv-producto-cant { font-size: 11px; color: var(--ink-muted); }
.rv-producto-sub { font-size: 12px; font-weight: 700; color: var(--green-600); font-variant-numeric: tabular-nums; flex-shrink: 0; }

/* Monto total */
.rv-monto-grande {
    font-family: 'Instrument Serif', serif; font-size: 32px;
    color: var(--green-700); letter-spacing: -1px; line-height: 1; margin-bottom: 6px;
}
.rv-monto-lbl { font-size: 11px; color: var(--ink-muted); font-weight: 500; }
.rv-pagador-tag {
    display: inline-block; margin-top: 10px;
    background: var(--green-50); border: 1px solid var(--border);
    border-radius: 6px; padding: 6px 12px; font-size: 12px; color: var(--ink-mid);
}
.rv-pagador-tag strong { color: var(--green-700); font-weight: 700; display: block; }

/* Footer con botones */
.rv-pedido-foot {
    padding: 14px 20px; border-top: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    background: var(--surface); flex-wrap: wrap; gap: 10px;
}
.rv-foot-left { font-size: 12px; color: var(--ink-muted); }
.rv-foot-left strong { color: var(--ink-mid); }
.rv-foot-actions { display: flex; gap: 10px; }

.btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: var(--radius-sm);
    font-size: 12px; font-weight: 600; border: none; cursor: pointer;
    text-decoration: none; transition: all .18s ease;
    letter-spacing: 0.2px; font-family: 'Sora', sans-serif;
}
.btn img { width: 13px; height: 13px; object-fit: contain; }
.btn-aceptar { background: var(--green-600); color: #fff; }
.btn-aceptar:hover { background: var(--green-700); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(59,109,17,0.3); }
.btn-rechazar { background: transparent; color: #7a1f1f; border: 1.5px solid #f5c2c2; }
.btn-rechazar:hover { background: #fceaea; }
.btn-ghost { background: transparent; color: var(--ink-mid); border: 1.5px solid var(--border); }
.btn-ghost:hover { background: var(--green-100); border-color: var(--green-500); color: var(--green-700); }

/* Modal confirmación */
.rv-modal-overlay {
    position: fixed; inset: 0; background: rgba(13,31,5,0.45);
    backdrop-filter: blur(3px); display: flex; align-items: center; justify-content: center;
    z-index: 9999; opacity: 0; pointer-events: none; transition: opacity .22s ease;
}
.rv-modal-overlay.active { opacity: 1; pointer-events: all; }
.rv-modal {
    background: var(--white); border-radius: var(--radius-lg);
    box-shadow: 0 24px 60px rgba(13,31,5,0.25);
    width: 360px; max-width: 90vw; overflow: hidden;
    transform: translateY(16px) scale(0.97);
    transition: transform .25s cubic-bezier(.34,1.4,.64,1);
}
.rv-modal-overlay.active .rv-modal { transform: translateY(0) scale(1); }
.rv-modal-header { padding: 22px 24px 0; display: flex; align-items: center; gap: 13px; }
.rv-modal-ico { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.rv-modal-ico-ok  { background: var(--green-100); }
.rv-modal-ico-err { background: #fceaea; }
.rv-modal-ico img { width: 20px; height: 20px; object-fit: contain; }
.rv-modal-title { font-family: 'Instrument Serif', serif; font-size: 20px; color: var(--ink); font-weight: 400; }
.rv-modal-body { padding: 14px 24px 22px; font-size: 13px; color: var(--ink-mid); line-height: 1.6; }
.rv-modal-body strong { color: var(--ink); font-weight: 600; }
.rv-modal-divider { height: 1px; background: var(--border); margin: 0 24px; }
.rv-modal-actions { padding: 16px 24px; display: flex; justify-content: flex-end; gap: 10px; background: var(--surface); }
.btn-modal-cancel { background: transparent; color: var(--ink-mid); border: 1.5px solid var(--border); padding: 8px 18px; border-radius: var(--radius-sm); font-family: 'Sora', sans-serif; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .17s; }
.btn-modal-cancel:hover { background: var(--green-100); border-color: var(--green-400); color: var(--green-700); }
.btn-modal-ok { border: none; padding: 8px 20px; border-radius: var(--radius-sm); font-family: 'Sora', sans-serif; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .17s; }
.btn-modal-ok.ok  { background: var(--green-600); color: #fff; }
.btn-modal-ok.ok:hover  { background: var(--green-700); }
.btn-modal-ok.err { background: #c0392b; color: #fff; }
.btn-modal-ok.err:hover { background: #a93226; }

/* Toast */
#rv-toast {
    position: fixed; bottom: 28px; right: 28px; z-index: 99999;
    background: var(--green-700); color: #fff;
    padding: 13px 20px; border-radius: var(--radius-sm);
    font-family: 'Sora', sans-serif; font-size: 13px; font-weight: 500;
    box-shadow: 0 8px 24px rgba(26,61,6,0.3);
    opacity: 0; transform: translateY(16px);
    transition: opacity .3s ease, transform .3s ease; pointer-events: none;
}

@media (max-width: 640px) {
    .rv-stats { grid-template-columns: repeat(2, 1fr); }
    .rv-hero h1 { font-size: 28px; }
}
</style>
@endpush

@section('content')

@php
$totalEsperando  = $pedidos->count();
@endphp

{{-- ── HERO ─────────────────────────────────────────── --}}
<div class="rv-hero">
    <div class="rv-hero-top">
        <div class="rv-logo"><img src="/images/logo.png" alt="Rayo Verde"></div>
        <div>
            <div class="rv-brand-name">Rayo Verde</div>
            <div class="rv-brand-sub">Panel Administrativo</div>
        </div>
    </div>
    <div class="rv-hero-body">
        <div class="rv-hero-eyebrow">Pedidos</div>
        <h1>Bandeja de <em>Pagos</em></h1>
        <p>Verifica y gestiona los pagos notificados por los clientes</p>
    </div>
    <div class="rv-stats">
        <div class="rv-stat">
            <div class="rv-stat-val"><span class="dot-amber"></span>{{ $totalEsperando }}</div>
            <div class="rv-stat-lbl">Por verificar</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val"><span class="dot-ok"></span>{{ $totalAceptados }}</div>
            <div class="rv-stat-lbl">Aceptados</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val"><span class="dot-off"></span>{{ $totalRechazados }}</div>
            <div class="rv-stat-lbl">Rechazados</div>
        </div>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="rv-flash rv-flash-success">✓ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="rv-flash rv-flash-error">✗ {{ session('error') }}</div>
@endif

{{-- ── LISTA DE PEDIDOS ─────────────────────────────── --}}
@forelse($pedidos as $pedido)

@php
$carrito = $pedido->carrito ?? [];
$cliente = $pedido->cliente;
$usuario = $cliente?->usuario;
@endphp

<div class="rv-pedido-card" id="pedido-{{ $pedido->id }}">

    {{-- Header --}}
    <div class="rv-pedido-head">
        <div class="rv-pedido-head-left">
            <div>
                <div class="rv-pedido-codigo">{{ $pedido->codigo }}</div>
                <div class="rv-pedido-hora">
                    Notificado el {{ \Carbon\Carbon::parse($pedido->confirmado_en)->format('d/m/Y') }}
                    a las {{ \Carbon\Carbon::parse($pedido->confirmado_en)->format('H:i') }}
                </div>
            </div>
        </div>
        <span class="rv-badge-espera">Esperando verificación</span>
    </div>

    {{-- Cuerpo 3 columnas --}}
    <div class="rv-pedido-body">

        {{-- Col 1: Cliente --}}
        <div class="rv-pedido-section">
            <div class="rv-section-title">Cliente</div>
            <div class="rv-cliente-nombre">
                {{ $usuario?->nombre ?? '—' }} {{ $usuario?->apellido ?? '' }}
            </div>
            <div class="rv-cliente-detalle">
                <strong>Correo:</strong> {{ $usuario?->correo ?? '—' }}
            </div>
            <div class="rv-cliente-detalle">
                <strong>Teléfono:</strong> {{ $cliente?->telefono ?? '—' }}
            </div>
            <div class="rv-cliente-detalle">
                <strong>Empresa:</strong> {{ $cliente?->empresa ?? '—' }}
            </div>
        </div>

        {{-- Col 2: Productos --}}
        <div class="rv-pedido-section">
            <div class="rv-section-title">Productos ({{ count($carrito) }})</div>
            @foreach($carrito as $item)
            <div class="rv-producto-item">
                @if(!empty($item['imagen_url']))
                    <img src="{{ $item['imagen_url'] }}" alt="{{ $item['nombre'] }}" class="rv-producto-thumb">
                @else
                    <div class="rv-producto-thumb-ph"><img src="/images/icono-producto.png" alt=""></div>
                @endif
                <span class="rv-producto-nombre">{{ $item['nombre'] }}</span>
                <span class="rv-producto-cant">× {{ $item['cantidad'] }}</span>
                <span class="rv-producto-sub">Bs. {{ number_format($item['subtotal'], 2) }}</span>
            </div>
            @endforeach
        </div>

        {{-- Col 3: Monto y pagador --}}
        <div class="rv-pedido-section">
            <div class="rv-section-title">Pago</div>
            <div class="rv-monto-grande">Bs. {{ number_format($pedido->total, 2) }}</div>
            <div class="rv-monto-lbl">Monto total del pedido</div>
            <div class="rv-pagador-tag">
                <strong>{{ $pedido->nombre_titular }}</strong>
                {{ $pedido->banco }}
            </div>
        </div>

    </div>

    {{-- Footer acciones --}}
    <div class="rv-pedido-foot">
        <div class="rv-foot-left">
            Titular: <strong>{{ $pedido->nombre_titular }}</strong> &nbsp;·&nbsp;
            Banco: <strong>{{ $pedido->banco }}</strong>
        </div>
        <div class="rv-foot-actions">
            <button class="btn btn-rechazar"
                onclick="abrirModalRechazar({{ $pedido->id }}, '{{ $pedido->codigo }}')">
                Rechazar
            </button>
            <button class="btn btn-aceptar"
                onclick="abrirModalAceptar({{ $pedido->id }}, '{{ $pedido->codigo }}', 'Bs. {{ number_format($pedido->total, 2) }}')">
                <img src="/images/icono-agregar.png" alt="" style="filter:brightness(0) invert(1);">
                Aceptar pago
            </button>
        </div>
    </div>

    {{-- Forms ocultos --}}
    <form id="form-aceptar-{{ $pedido->id }}"
          action="{{ route('admin.pedidos.aceptar', $pedido->id) }}"
          method="POST" style="display:none;">
        @csrf
    </form>
    <form id="form-rechazar-{{ $pedido->id }}"
          action="{{ route('admin.pedidos.rechazar', $pedido->id) }}"
          method="POST" style="display:none;">
        @csrf
    </form>

</div>
@empty
<div class="rv-empty-bandeja">
    <img src="/images/icono-envio.png" alt="">
    <p>No hay pedidos pendientes de verificación.</p>
</div>
@endforelse

{{-- ── MODALES ──────────────────────────────────────── --}}

{{-- Modal aceptar --}}
<div class="rv-modal-overlay" id="modalAceptar">
    <div class="rv-modal">
        <div class="rv-modal-header">
            <div class="rv-modal-ico rv-modal-ico-ok">
                <img src="/images/icono-agregar.png" alt="">
            </div>
            <div class="rv-modal-title">Aceptar pago</div>
        </div>
        <div class="rv-modal-body">
            <p>¿Confirmas que el pago del pedido <strong id="modalAceptarCodigo"></strong> fue recibido correctamente?</p>
            <p style="margin-top:8px; color: var(--ink-muted);">Monto: <strong id="modalAceptarMonto"></strong></p>
            <p style="margin-top:8px; color: var(--ink-muted);">Al aceptar, el pedido quedará registrado en el sistema y se sumará a las ventas.</p>
        </div>
        <div class="rv-modal-divider"></div>
        <div class="rv-modal-actions">
            <button class="btn-modal-cancel" onclick="cerrarModales()">Cancelar</button>
            <button class="btn-modal-ok ok" onclick="ejecutarAceptar()">
                Sí, aceptar pago
            </button>
        </div>
    </div>
</div>

{{-- Modal rechazar --}}
<div class="rv-modal-overlay" id="modalRechazar">
    <div class="rv-modal">
        <div class="rv-modal-header">
            <div class="rv-modal-ico rv-modal-ico-err">
                <img src="/images/icono-basurero.png" alt="">
            </div>
            <div class="rv-modal-title">Rechazar pago</div>
        </div>
        <div class="rv-modal-body">
            <p>¿Confirmas que el pago del pedido <strong id="modalRechazarCodigo"></strong> <strong>no</strong> fue encontrado en el extracto?</p>
            <p style="margin-top:8px; color: var(--ink-muted);">El pedido quedará marcado como rechazado y no se registrará ninguna venta.</p>
        </div>
        <div class="rv-modal-divider"></div>
        <div class="rv-modal-actions">
            <button class="btn-modal-cancel" onclick="cerrarModales()">Cancelar</button>
            <button class="btn-modal-ok err" onclick="ejecutarRechazar()">
                Sí, rechazar
            </button>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="rv-toast"><span id="rv-toast-msg">Acción realizada.</span></div>

@push('scripts')
<script>
let pendienteId    = null;
let pendienteCodigo = null;

// ── Aceptar ───────────────────────────────────────────
function abrirModalAceptar(id, codigo, monto) {
    pendienteId     = id;
    pendienteCodigo = codigo;
    document.getElementById('modalAceptarCodigo').textContent = codigo;
    document.getElementById('modalAceptarMonto').textContent  = monto;
    document.getElementById('modalAceptar').classList.add('active');
}

function ejecutarAceptar() {
    if (!pendienteId) return;
    cerrarModales();
    animarYSubmit('form-aceptar-' + pendienteId, 'pedido-' + pendienteId);
}

// ── Rechazar ──────────────────────────────────────────
function abrirModalRechazar(id, codigo) {
    pendienteId     = id;
    pendienteCodigo = codigo;
    document.getElementById('modalRechazarCodigo').textContent = codigo;
    document.getElementById('modalRechazar').classList.add('active');
}

function ejecutarRechazar() {
    if (!pendienteId) return;
    cerrarModales();
    animarYSubmit('form-rechazar-' + pendienteId, 'pedido-' + pendienteId);
}

// ── Helpers ───────────────────────────────────────────
function cerrarModales() {
    document.getElementById('modalAceptar').classList.remove('active');
    document.getElementById('modalRechazar').classList.remove('active');
    pendienteId = null;
}

function animarYSubmit(formId, cardId) {
    const card = document.getElementById(cardId);
    if (card) {
        card.style.transition = 'opacity .35s ease, transform .35s ease';
        card.style.opacity    = '0';
        card.style.transform  = 'translateX(24px)';
    }
    setTimeout(() => {
        const form = document.getElementById(formId);
        if (form) form.submit();
    }, 370);
}

function mostrarToast(msg) {
    const t = document.getElementById('rv-toast');
    document.getElementById('rv-toast-msg').textContent = msg;
    t.style.opacity   = '1';
    t.style.transform = 'translateY(0)';
    setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateY(16px)'; }, 3200);
}

// Cerrar con Escape o click fuera
['modalAceptar','modalRechazar'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) cerrarModales();
    });
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModales(); });

// Toast post-redirect
@if(session('success'))
    document.addEventListener('DOMContentLoaded', () => mostrarToast("{{ session('success') }}"));
@endif
</script>
@endpush

@endsection