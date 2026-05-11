@extends('layout')
@section('title', 'Bandeja de Pedidos — Rayo Verde')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/estilosDash.css') }}">
@endpush

@section('content')

@php
$totalEsperando  = $pedidos->count();
@endphp

{{-- ── HERO ─────────────────────────────────────────── --}}
<div class="rv-hero">
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