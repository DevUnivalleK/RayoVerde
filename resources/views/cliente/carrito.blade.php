@extends('layout')
@section('title', 'Mi Carrito — Rayo Verde')

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

/* ─── Hero ─────────────────────────────────────────── */
.rv-hero {
    position: relative;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 60%, var(--green-600) 100%);
    border-radius: var(--radius-lg); padding: 32px 36px 36px;
    margin-bottom: 24px; overflow: hidden; box-shadow: var(--shadow-hero);
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
.rv-hero-top { display: flex; align-items: center; gap: 14px; margin-bottom: 20px; position: relative; }
.rv-logo {
    width: 48px; height: 48px; border-radius: 12px;
    background: rgba(255,255,255,0.12); border: 1.5px solid rgba(255,255,255,0.25);
    display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;
}
.rv-logo img { width: 100%; height: 100%; object-fit: contain; }
.rv-brand-name { font-family: 'Instrument Serif', serif; font-size: 20px; color: #fff; }
.rv-brand-sub { font-size: 10px; color: rgba(255,255,255,0.45); letter-spacing: 2px; text-transform: uppercase; margin-top: 2px; }
.rv-hero-body { position: relative; }
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

/* ─── Flash ──────────────────────────────────────────── */
.rv-flash {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 18px; border-radius: var(--radius-sm);
    font-size: 13px; font-weight: 500; margin-bottom: 16px;
}
.rv-flash-success { background: var(--green-100); color: var(--green-700); border: 1px solid #b8d9a0; }
.rv-flash-error   { background: #fceaea; color: #7a1f1f; border: 1px solid #f5c2c2; }

/* ─── Layout ─────────────────────────────────────────── */
.rv-carrito-layout {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    align-items: start;
}
@media (max-width: 860px) { .rv-carrito-layout { grid-template-columns: 1fr; } }

/* ─── Card base ──────────────────────────────────────── */
.rv-card {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius-md); overflow: hidden;
    box-shadow: var(--shadow-card); margin-bottom: 16px;
}
.rv-card:last-child { margin-bottom: 0; }
.rv-card-head {
    padding: 16px 20px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    background: var(--white);
}
.rv-card-title { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 600; color: var(--ink); }
.rv-card-icon {
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    background: var(--green-100); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.rv-card-icon img { width: 18px; height: 18px; object-fit: contain; }
.rv-badge {
    font-size: 10px; background: var(--green-100); color: var(--green-700);
    padding: 3px 10px; border-radius: 20px; font-weight: 600;
}

/* ─── Items del carrito ──────────────────────────────── */
.rv-item {
    display: flex; align-items: center; gap: 16px;
    padding: 16px 20px; border-bottom: 1px solid #f0f4eb;
    transition: background .15s;
}
.rv-item:last-child { border-bottom: none; }
.rv-item:hover { background: var(--green-50); }

.rv-item-img {
    width: 64px; height: 64px; border-radius: var(--radius-sm);
    object-fit: cover; border: 1px solid var(--border); flex-shrink: 0;
    background: var(--green-50);
}
.rv-item-img-placeholder {
    width: 64px; height: 64px; border-radius: var(--radius-sm);
    background: var(--green-50); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.rv-item-img-placeholder img { width: 28px; height: 28px; object-fit: contain; opacity: 0.3; }

.rv-item-info { flex: 1; min-width: 0; }
.rv-item-nombre { font-size: 14px; font-weight: 600; color: var(--ink); margin-bottom: 4px; }
.rv-item-precio-unit { font-size: 12px; color: var(--ink-muted); }
.rv-item-precio-unit strong { color: var(--green-600); font-weight: 700; }

.rv-item-qty {
    display: flex; align-items: center; gap: 6px; flex-shrink: 0;
}
.rv-item-qty-val {
    font-size: 13px; font-weight: 700; color: var(--ink);
    min-width: 28px; text-align: center;
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 6px; padding: 4px 8px;
}
.rv-item-qty-lbl { font-size: 10px; color: var(--ink-muted); letter-spacing: 0.5px; }

.rv-item-subtotal {
    font-family: 'Instrument Serif', serif; font-size: 20px;
    color: var(--green-700); letter-spacing: -0.5px; flex-shrink: 0; min-width: 80px; text-align: right;
}

.rv-item-remove {
    flex-shrink: 0; width: 32px; height: 32px; border-radius: var(--radius-sm);
    border: 1px solid var(--border); background: var(--white);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all .17s;
}
.rv-item-remove img { width: 14px; height: 14px; object-fit: contain; }
.rv-item-remove:hover { background: #fceaea; border-color: #f5c2c2; transform: scale(1.08); }

/* ─── Carrito vacío ──────────────────────────────────── */
.rv-carrito-vacio {
    padding: 60px 20px; text-align: center; color: var(--ink-muted);
}
.rv-carrito-vacio img { width: 56px; height: 56px; object-fit: contain; opacity: 0.2; margin: 0 auto 16px; display: block; }
.rv-carrito-vacio p { font-size: 14px; margin-bottom: 20px; }

/* ─── Resumen (columna derecha) ──────────────────────── */
.rv-resumen-card {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius-md); overflow: hidden;
    box-shadow: var(--shadow-card); position: sticky; top: 20px;
}
.rv-resumen-head {
    padding: 16px 20px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 10px;
    font-size: 14px; font-weight: 600; color: var(--ink);
    background: var(--white);
}
.rv-resumen-body { padding: 20px; }

.rv-resumen-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 8px 0; border-bottom: 1px solid #f0f4eb; font-size: 13px;
}
.rv-resumen-row:last-child { border-bottom: none; }
.rv-resumen-row .label { color: var(--ink-muted); }
.rv-resumen-row .value { font-weight: 600; color: var(--ink); font-variant-numeric: tabular-nums; }

.rv-resumen-total {
    display: flex; justify-content: space-between; align-items: center;
    padding: 16px 20px; border-top: 2px solid var(--border);
    background: var(--green-50);
}
.rv-resumen-total .label { font-size: 13px; font-weight: 700; color: var(--ink); }
.rv-resumen-total .value {
    font-family: 'Instrument Serif', serif; font-size: 26px;
    color: var(--green-700); letter-spacing: -0.5px;
}

.rv-resumen-foot { padding: 16px 20px; display: flex; flex-direction: column; gap: 10px; background: var(--surface); }

/* ─── Botones ────────────────────────────────────────── */
.btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    padding: 10px 20px; border-radius: var(--radius-sm);
    font-size: 12px; font-weight: 600; border: none; cursor: pointer;
    text-decoration: none; transition: all .18s ease;
    letter-spacing: 0.2px; font-family: 'Sora', sans-serif; width: 100%;
}
.btn img { width: 14px; height: 14px; object-fit: contain; }
.btn-dark { background: var(--green-600); color: #fff; }
.btn-dark:hover { background: var(--green-700); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(59,109,17,0.3); }
.btn-ghost { background: transparent; color: var(--ink-mid); border: 1.5px solid var(--border); }
.btn-ghost:hover { background: var(--green-100); border-color: var(--green-500); color: var(--green-700); }
.btn-danger { background: transparent; color: #7a1f1f; border: 1.5px solid #f5c2c2; }
.btn-danger:hover { background: #fceaea; }
</style>
@endpush

@section('content')

@php
$cantidadTotal = collect($items)->sum('cantidad');
$totalItems    = count($items);
@endphp

{{-- ── HERO ─────────────────────────────────────────── --}}
<div class="rv-hero">
    <div class="rv-hero-top">
        <div class="rv-logo"><img src="/images/logo.png" alt="Rayo Verde"></div>
        <div>
            <div class="rv-brand-name">Rayo Verde</div>
            <div class="rv-brand-sub">Aceites Naturales</div>
        </div>
    </div>
    <div class="rv-hero-body">
        <div class="rv-hero-eyebrow">Pedido</div>
        <h1>Mi <em>Carrito</em></h1>
        <p>Revisa tus productos antes de continuar con el pago</p>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="rv-flash rv-flash-success">✓ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="rv-flash rv-flash-error">✗ {{ session('error') }}</div>
@endif

@if(empty($items))
{{-- ── CARRITO VACÍO ────────────────────────────────── --}}
<div class="rv-card">
    <div class="rv-carrito-vacio">
        <img src="/images/icono-envio.png" alt="">
        <p>Tu carrito está vacío.</p>
        <a href="{{ route('cliente.catalogo') }}" class="btn btn-dark" style="width:auto; display:inline-flex;">
            <img src="/images/icono-region.png" alt="" style="filter:brightness(0) invert(1);">
            Ver catálogo
        </a>
    </div>
</div>

@else
{{-- ── CARRITO CON ITEMS ────────────────────────────── --}}
<div class="rv-carrito-layout">

    {{-- Columna izquierda: lista de productos --}}
    <div>
        <div class="rv-card">
            <div class="rv-card-head">
                <div class="rv-card-title">
                    <div class="rv-card-icon"><img src="/images/icono-envio.png" alt=""></div>
                    Productos en el carrito
                </div>
                <span class="rv-badge">{{ $totalItems }} {{ $totalItems === 1 ? 'producto' : 'productos' }}</span>
            </div>

            @foreach($items as $item)
            <div class="rv-item">

                {{-- Imagen --}}
                @if($item['imagen_url'])
                    <img src="{{ $item['imagen_url'] }}" alt="{{ $item['nombre'] }}" class="rv-item-img">
                @else
                    <div class="rv-item-img-placeholder">
                        <img src="/images/icono-producto.png" alt="">
                    </div>
                @endif

                {{-- Info --}}
                <div class="rv-item-info">
                    <div class="rv-item-nombre">{{ $item['nombre'] }}</div>
                    <div class="rv-item-precio-unit">
                        Precio unitario: <strong>Bs. {{ number_format($item['precio'], 2) }}</strong>
                    </div>
                </div>

                {{-- Cantidad --}}
                <div class="rv-item-qty">
                    <span class="rv-item-qty-lbl">Cant.</span>
                    <span class="rv-item-qty-val">{{ $item['cantidad'] }}</span>
                </div>

                {{-- Subtotal --}}
                <div class="rv-item-subtotal">
                    Bs. {{ number_format($item['subtotal'], 2) }}
                </div>

                {{-- Quitar --}}
                <form action="{{ route('cliente.carrito.quitar', $item['id_producto']) }}"
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rv-item-remove" title="Quitar">
                        <img src="/images/icono-basurero.png" alt="Quitar">
                    </button>
                </form>

            </div>
            @endforeach
        </div>

        {{-- Vaciar carrito --}}
        <form action="{{ route('cliente.carrito.vaciar') }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" style="width:auto; display:inline-flex;">
                Vaciar carrito
            </button>
        </form>
    </div>

    {{-- Columna derecha: resumen --}}
    <div>
        <div class="rv-resumen-card">
            <div class="rv-resumen-head">
                <div class="rv-card-icon"><img src="/images/icono-agregar.png" alt=""></div>
                Resumen del pedido
            </div>

            <div class="rv-resumen-body">
                @foreach($items as $item)
                <div class="rv-resumen-row">
                    <span class="label">{{ $item['nombre'] }} × {{ $item['cantidad'] }}</span>
                    <span class="value">Bs. {{ number_format($item['subtotal'], 2) }}</span>
                </div>
                @endforeach
            </div>

            <div class="rv-resumen-total">
                <span class="label">Total</span>
                <span class="value">Bs. {{ number_format($total, 2) }}</span>
            </div>

            <div class="rv-resumen-foot">
                <a href="{{ route('cliente.checkout') }}" class="btn btn-dark">
                    <img src="/images/icono-agregar.png" alt="" style="filter:brightness(0) invert(1);">
                    Pagar carrito
                </a>
                <a href="{{ route('cliente.catalogo') }}" class="btn btn-ghost">
                    <img src="/images/icono-regresar.png" alt="">
                    Seguir comprando
                </a>
            </div>
        </div>
    </div>

</div>
@endif

@endsection