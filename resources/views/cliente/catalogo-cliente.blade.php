@extends('layout')
@section('title', 'Catálogo — Rayo Verde')

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
    --stock-low: #c0392b;
    --stock-mid: #c47c1a;
    --stock-ok:  #2c5a0e;
    --radius-sm: 8px;
    --radius-md: 16px;
    --radius-lg: 22px;
    --shadow-card:  0 4px 24px rgba(59,109,17,0.10), 0 1px 4px rgba(0,0,0,0.05);
    --shadow-hover: 0 16px 48px rgba(26,61,6,0.18), 0 4px 12px rgba(0,0,0,0.08);
    --shadow-hero:  0 8px 40px rgba(26,61,6,0.22);
}

body { font-family: 'Sora', sans-serif; background: var(--surface); color: var(--ink); line-height: 1.5; }

/* ─── Hero ─────────────────────────────────────────── */
.rv-hero {
    position: relative;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 60%, var(--green-600) 100%);
    border-radius: var(--radius-lg); padding: 36px 40px 0;
    margin-bottom: 28px; overflow: hidden; box-shadow: var(--shadow-hero);
}
.rv-hero::before {
    content: ''; position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 60% 80% at 90% -10%, rgba(107,184,58,0.22) 0%, transparent 60%),
        radial-gradient(ellipse 40% 60% at -5% 100%, rgba(255,255,255,0.06) 0%, transparent 50%);
    pointer-events: none;
}
.rv-hero::after {
    content: ''; position: absolute; right: -80px; bottom: -80px;
    width: 320px; height: 320px; border-radius: 50%;
    border: 50px solid rgba(255,255,255,0.04);
}
.rv-hero-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; position: relative; }
.rv-hero-brand { display: flex; align-items: center; gap: 14px; }
.rv-logo {
    width: 52px; height: 52px; border-radius: 13px;
    background: rgba(255,255,255,0.13); border: 1.5px solid rgba(255,255,255,0.28);
    display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;
}
.rv-logo img { width: 100%; height: 100%; object-fit: contain; }
.rv-brand-name { font-family: 'Instrument Serif', serif; font-size: 22px; color: #fff; letter-spacing: -0.3px; }
.rv-brand-sub { font-size: 10px; color: rgba(255,255,255,0.45); letter-spacing: 2px; text-transform: uppercase; margin-top: 2px; }

/* Carrito en el hero */
.rv-carrito-btn {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 18px; border-radius: var(--radius-sm);
    background: rgba(255,255,255,0.12); border: 1.5px solid rgba(255,255,255,0.25);
    color: #fff; text-decoration: none; font-size: 13px; font-weight: 600;
    transition: all .18s; position: relative; flex-shrink: 0;
}
.rv-carrito-btn:hover { background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.4); }
.rv-carrito-btn img { width: 18px; height: 18px; object-fit: contain; filter: brightness(0) invert(1); }
.rv-carrito-badge {
    position: absolute; top: -7px; right: -7px;
    width: 20px; height: 20px; border-radius: 50%;
    background: var(--green-400); color: var(--green-900);
    font-size: 10px; font-weight: 800; display: flex; align-items: center; justify-content: center;
}

.rv-hero-body { position: relative; margin-bottom: 36px; }
.rv-hero-eyebrow {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 10px; letter-spacing: 2.5px; text-transform: uppercase;
    color: var(--green-400); margin-bottom: 12px;
}
.rv-hero-eyebrow::before { content: ''; display: block; width: 24px; height: 1.5px; background: var(--green-400); }
.rv-hero h1 {
    font-family: 'Instrument Serif', serif; font-size: 44px; color: #fff;
    font-weight: 400; letter-spacing: -1.5px; line-height: 1.05; margin-bottom: 10px;
}
.rv-hero h1 em { font-style: italic; color: var(--green-400); }
.rv-hero-desc { color: rgba(255,255,255,0.55); font-size: 14px; font-weight: 300; }

.rv-stats { display: grid; grid-template-columns: repeat(3, 1fr); border-top: 1px solid rgba(255,255,255,0.1); }
.rv-stat { padding: 20px 28px; border-right: 1px solid rgba(255,255,255,0.08); transition: background .2s; }
.rv-stat:last-child { border-right: none; }
.rv-stat:hover { background: rgba(255,255,255,0.05); }
.rv-stat-val { font-family: 'Instrument Serif', serif; font-size: 30px; color: #fff; letter-spacing: -1px; line-height: 1; }
.rv-stat-lbl { font-size: 10px; color: rgba(255,255,255,0.4); margin-top: 5px; letter-spacing: 1px; text-transform: uppercase; }

/* ─── Flash ──────────────────────────────────────────── */
.rv-flash {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 18px; border-radius: var(--radius-sm);
    font-size: 13px; font-weight: 500; margin-bottom: 16px;
}
.rv-flash-success { background: var(--green-100); color: var(--green-700); border: 1px solid #b8d9a0; }
.rv-flash-error   { background: #fceaea; color: #7a1f1f; border: 1px solid #f5c2c2; }

/* ─── Buscador ───────────────────────────────────────── */
.rv-search-wrap { margin-bottom: 8px; }
.rv-search-bar { display: flex; align-items: center; gap: 10px; }
.rv-search {
    flex: 1; display: flex; align-items: center; gap: 10px;
    background: var(--white); border: 1.5px solid var(--border);
    border-radius: var(--radius-sm); padding: 0 16px;
    box-shadow: var(--shadow-card); transition: border-color .2s;
}
.rv-search:focus-within { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(79,144,32,0.1); }
.rv-search-icon { width: 16px; height: 16px; object-fit: contain; opacity: 0.4; flex-shrink: 0; }
.rv-search input {
    flex: 1; border: none; outline: none; padding: 13px 0;
    font-family: 'Sora', sans-serif; font-size: 13px; color: var(--ink); background: transparent;
}
.rv-search input::placeholder { color: var(--ink-muted); }

.rv-filter-btn {
    display: flex; align-items: center; gap: 8px;
    padding: 0 18px; height: 48px;
    background: var(--white); border: 1.5px solid var(--border);
    border-radius: var(--radius-sm); cursor: pointer;
    font-family: 'Sora', sans-serif; font-size: 12px; font-weight: 600;
    color: var(--ink-mid); white-space: nowrap; box-shadow: var(--shadow-card); transition: all .18s;
}
.rv-filter-btn img { width: 16px; height: 16px; object-fit: contain; opacity: 0.6; }
.rv-filter-btn:hover, .rv-filter-btn.active { border-color: var(--green-500); background: var(--green-100); color: var(--green-700); }
.rv-result-count { font-size: 12px; color: var(--ink-muted); font-weight: 500; margin-top: 8px; display: block; }

/* Panel filtro precio */
.rv-filter-panel {
    background: var(--white); border: 1.5px solid var(--border);
    border-radius: var(--radius-md); padding: 0; box-shadow: var(--shadow-card);
    margin-top: 10px; max-height: 0; overflow: hidden;
    transition: max-height .35s cubic-bezier(.4,0,.2,1), padding .25s ease;
}
.rv-filter-panel.open { max-height: 200px; padding: 20px 24px 22px; }
.rv-filter-title {
    font-size: 11px; font-weight: 700; color: var(--ink-muted);
    letter-spacing: 1.2px; text-transform: uppercase; margin-bottom: 16px;
    display: flex; align-items: center; gap: 8px;
}
.rv-filter-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }
.rv-slider-wrap { position: relative; height: 36px; display: flex; align-items: center; }
.rv-slider-track { position: absolute; left: 0; right: 0; height: 5px; background: var(--border); border-radius: 3px; }
.rv-slider-range {
    position: absolute; height: 5px;
    background: linear-gradient(90deg, var(--green-600), var(--green-400));
    border-radius: 3px; pointer-events: none;
}
.rv-slider-input {
    position: absolute; width: 100%; -webkit-appearance: none; appearance: none;
    height: 5px; background: transparent; outline: none; pointer-events: none;
}
.rv-slider-input::-webkit-slider-thumb {
    -webkit-appearance: none; width: 20px; height: 20px; border-radius: 50%;
    background: var(--white); border: 2.5px solid var(--green-600);
    box-shadow: 0 2px 8px rgba(59,109,17,0.25); cursor: pointer; pointer-events: all;
    transition: transform .15s;
}
.rv-slider-input::-webkit-slider-thumb:hover { transform: scale(1.15); }
.rv-slider-labels { display: flex; justify-content: space-between; align-items: center; margin-top: 12px; }
.rv-slider-val {
    font-size: 13px; font-weight: 700; color: var(--green-700);
    background: var(--green-50); border: 1px solid var(--border);
    padding: 4px 10px; border-radius: 6px; min-width: 80px; text-align: center;
}
.rv-slider-sep { font-size: 12px; color: var(--ink-muted); }
.rv-clear-btn {
    margin-top: 14px; background: none; border: none;
    font-family: 'Sora', sans-serif; font-size: 11px; font-weight: 600;
    color: var(--ink-muted); cursor: pointer; transition: color .15s;
}
.rv-clear-btn:hover { color: var(--green-600); }

/* ─── Grid de tarjetas ───────────────────────────────── */
.rv-catalogo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px; margin-top: 24px; margin-bottom: 40px;
}

/* ─── Tarjeta ────────────────────────────────────────── */
.rv-card {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius-md); overflow: hidden;
    box-shadow: var(--shadow-card); display: flex; flex-direction: column;
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
}
.rv-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-hover); border-color: #c8deb8; }

.rv-card-img {
    position: relative; width: 100%; padding-top: 100%;
    overflow: hidden; background: var(--green-50); flex-shrink: 0;
}
.rv-card-img img.foto {
    position: absolute; inset: 0; width: 100%; height: 100%;
    object-fit: cover; transition: transform .4s ease;
}
.rv-card:hover .rv-card-img img.foto { transform: scale(1.06); }
.rv-card-img .placeholder {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 12px; color: var(--ink-muted);
}
.rv-card-img .placeholder img { width: 52px; height: 52px; object-fit: contain; opacity: 0.25; }
.rv-card-img .placeholder span { font-size: 12px; opacity: 0.5; }

.rv-stock-badge {
    position: absolute; top: 12px; right: 12px;
    padding: 4px 11px; border-radius: 20px;
    font-size: 11px; font-weight: 700; letter-spacing: 0.3px;
    backdrop-filter: blur(6px); pointer-events: none;
}
.sc-ok  { background: rgba(237,245,225,0.92); color: var(--stock-ok); }
.sc-mid { background: rgba(253,240,219,0.92); color: var(--stock-mid); }
.sc-low { background: rgba(252,234,234,0.92); color: var(--stock-low); }
.sc-out { background: rgba(30,30,30,0.72);    color: #fff; }

.rv-card.agotado .rv-card-img::after {
    content: 'AGOTADO'; position: absolute; inset: 0;
    background: rgba(13,31,5,0.55);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 14px; font-weight: 700; letter-spacing: 3px;
    backdrop-filter: blur(2px);
}

.rv-card-body { padding: 18px 20px 0; flex: 1; display: flex; flex-direction: column; gap: 4px; }
.rv-card-nombre { font-size: 15px; font-weight: 600; color: var(--ink); letter-spacing: -0.3px; line-height: 1.3; }
.rv-card-precio {
    font-family: 'Instrument Serif', serif; font-size: 26px;
    color: var(--green-700); letter-spacing: -0.8px; line-height: 1; margin-top: 6px;
}
.rv-card-precio small { font-family: 'Sora', sans-serif; font-size: 12px; color: var(--ink-muted); font-weight: 400; margin-left: 2px; }

.rv-stock-row { display: flex; align-items: center; gap: 7px; margin-top: 8px; }
.rv-stock-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.rv-stock-text { font-size: 12px; font-weight: 600; }
.sc-ok  .rv-stock-dot  { background: var(--green-500); box-shadow: 0 0 5px rgba(79,144,32,0.5); }
.sc-ok  .rv-stock-text  { color: var(--stock-ok); }
.sc-mid .rv-stock-dot  { background: var(--stock-mid); }
.sc-mid .rv-stock-text  { color: var(--stock-mid); }
.sc-low .rv-stock-dot  { background: var(--stock-low); animation: pulso 1.2s ease-in-out infinite; }
.sc-low .rv-stock-text  { color: var(--stock-low); font-weight: 700; }
.sc-out .rv-stock-dot  { background: #aaa; }
.sc-out .rv-stock-text  { color: #999; }
@keyframes pulso { 0%,100% { opacity:1;transform:scale(1); } 50% { opacity:.45;transform:scale(1.35); } }

.rv-stock-bar-wrap { height: 4px; background: var(--border); border-radius: 3px; overflow: hidden; margin-top: 4px; }
.rv-stock-bar { height: 100%; border-radius: 3px; }
.bar-ok  { background: linear-gradient(90deg, var(--green-600), var(--green-400)); }
.bar-mid { background: linear-gradient(90deg, #b36a10, #e8a030); }
.bar-low { background: linear-gradient(90deg, #a93226, #e74c3c); }
.bar-out { background: #ccc; }

/* Botón agregar al carrito */
.rv-card-foot { padding: 14px 20px 18px; margin-top: auto; }

.btn-agregar {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 10px 0;
    background: var(--green-600); color: #fff;
    border: none; border-radius: var(--radius-sm);
    font-family: 'Sora', sans-serif; font-size: 12px; font-weight: 600;
    cursor: pointer; transition: all .17s ease; letter-spacing: 0.2px;
    text-decoration: none;
}
.btn-agregar img { width: 14px; height: 14px; object-fit: contain; filter: brightness(0) invert(1); }
.btn-agregar:hover { background: var(--green-700); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59,109,17,0.3); }
.btn-agregar:disabled, .btn-agregar.disabled {
    background: #ccc; cursor: not-allowed; transform: none; box-shadow: none;
}

/* Selector de cantidad */
.rv-qty-row {
    display: flex; align-items: center; gap: 8px; margin-bottom: 10px;
}
.rv-qty-label { font-size: 11px; font-weight: 600; color: var(--ink-muted); letter-spacing: 0.5px; text-transform: uppercase; }
.rv-qty-ctrl {
    display: flex; align-items: center;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    overflow: hidden; background: var(--white);
}
.rv-qty-ctrl button {
    width: 30px; height: 30px; border: none; background: var(--surface);
    color: var(--ink-mid); font-size: 16px; cursor: pointer; transition: background .15s;
    display: flex; align-items: center; justify-content: center;
}
.rv-qty-ctrl button:hover { background: var(--green-100); color: var(--green-700); }
.rv-qty-ctrl input {
    width: 38px; border: none; outline: none; text-align: center;
    font-family: 'Sora', sans-serif; font-size: 13px; font-weight: 600;
    color: var(--ink); background: transparent;
}

/* ─── Empty state ────────────────────────────────────── */
.rv-empty-state { grid-column: 1/-1; padding: 80px 20px; text-align: center; color: var(--ink-muted); }
.rv-empty-state img { width: 64px; height: 64px; object-fit: contain; opacity: 0.2; margin: 0 auto 20px; display: block; }

/* ─── Responsive ─────────────────────────────────────── */
@media (max-width: 680px) {
    .rv-hero { padding: 24px 20px 0; }
    .rv-hero h1 { font-size: 32px; }
    .rv-stats { grid-template-columns: repeat(2, 1fr); }
    .rv-stat:nth-child(3) { grid-column:1/-1; border-top:1px solid rgba(255,255,255,0.08); border-right:none; }
    .rv-catalogo-grid { grid-template-columns: repeat(auto-fill, minmax(220px,1fr)); gap: 14px; }
}
@media (max-width: 420px) {
    .rv-catalogo-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .rv-card-precio { font-size: 20px; }
    .rv-card-nombre { font-size: 13px; }
    .rv-card-body { padding: 12px 12px 0; }
    .rv-card-foot { padding: 10px 12px 14px; }
}
</style>
@endpush

@section('content')

@php
$STOCK_LOW  = 5;
$STOCK_MID  = 15;
$maxStock   = $productos->max('cantidad') ?: 1;
$total      = $productos->count();
$conStock   = $productos->where('cantidad', '>', 0)->count();
$precioMin  = (int) floor($productos->min('precio'));
$precioMax  = (int) ceil($productos->max('precio'));
$itemsCarrito = collect(session('carrito', []))->sum('cantidad');
@endphp

{{-- ── HERO ─────────────────────────────────────────── --}}
<div class="rv-hero">
    <div class="rv-hero-top">
        <div class="rv-hero-brand">
            <div class="rv-logo"><img src="/images/logo.png" alt="Rayo Verde"></div>
            <div>
                <div class="rv-brand-name">Rayo Verde</div>
                <div class="rv-brand-sub">Aceites Naturales</div>
            </div>
        </div>

        {{-- Botón carrito con badge --}}
        <a href="{{ route('cliente.carrito') }}" class="rv-carrito-btn">
            <img src="/images/icono-envio.png" alt="">
            Mi carrito
            @if($itemsCarrito > 0)
                <span class="rv-carrito-badge">{{ $itemsCarrito }}</span>
            @endif
        </a>
    </div>

    <div class="rv-hero-body">
        <div class="rv-hero-eyebrow">Catálogo</div>
        <h1>Elige tus <em>Productos</em></h1>
        <p class="rv-hero-desc">Agrega lo que necesitas a tu carrito y realiza tu pedido fácilmente</p>
    </div>

    <div class="rv-stats">
        <div class="rv-stat">
            <div class="rv-stat-val">{{ $total }}</div>
            <div class="rv-stat-lbl">Productos</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">{{ $conStock }}</div>
            <div class="rv-stat-lbl">Disponibles</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">Bs. {{ $precioMin }}</div>
            <div class="rv-stat-lbl">Desde</div>
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

{{-- ── BUSCADOR + FILTRO ────────────────────────────── --}}
<div class="rv-search-wrap">
    <div class="rv-search-bar">
        <div class="rv-search">
            <img src="/images/icono-buscar.png" alt="" class="rv-search-icon">
            <input type="text" id="buscador" placeholder="Buscar por nombre..." oninput="aplicarFiltros()">
        </div>
        <button class="rv-filter-btn" id="btnFiltro" onclick="toggleFiltro()">
            <img src="/images/filter.png" alt="Filtrar">
            Filtrar precio
        </button>
    </div>

    <div class="rv-filter-panel" id="filterPanel">
        <div class="rv-filter-title">Rango de precio</div>
        <div class="rv-slider-wrap">
            <div class="rv-slider-track"></div>
            <div class="rv-slider-range" id="sliderRange"></div>
            <input type="range" class="rv-slider-input" id="sliderMin"
                   min="{{ $precioMin }}" max="{{ $precioMax }}" value="{{ $precioMin }}"
                   oninput="moverSlider()">
            <input type="range" class="rv-slider-input" id="sliderMax"
                   min="{{ $precioMin }}" max="{{ $precioMax }}" value="{{ $precioMax }}"
                   oninput="moverSlider()">
        </div>
        <div class="rv-slider-labels">
            <span class="rv-slider-val" id="labelMin">Bs. {{ $precioMin }}</span>
            <span class="rv-slider-sep">—</span>
            <span class="rv-slider-val" id="labelMax">Bs. {{ $precioMax }}</span>
        </div>
        <button class="rv-clear-btn" onclick="limpiarFiltros()">✕ Limpiar filtros</button>
    </div>

    <span class="rv-result-count" id="resultCount">{{ $total }} productos</span>
</div>

{{-- ── GRID ─────────────────────────────────────────── --}}
<div class="rv-catalogo-grid" id="catalogoGrid">

    @forelse($productos as $producto)
    @php
        $qty = (int) $producto->cantidad;
        if ($qty <= 0)              $sc = 'sc-out';
        elseif ($qty <= $STOCK_LOW) $sc = 'sc-low';
        elseif ($qty <= $STOCK_MID) $sc = 'sc-mid';
        else                        $sc = 'sc-ok';

        if ($qty <= 0)              $bc = 'bar-out';
        elseif ($qty <= $STOCK_LOW) $bc = 'bar-low';
        elseif ($qty <= $STOCK_MID) $bc = 'bar-mid';
        else                        $bc = 'bar-ok';

        $pct = min(100, round($qty / $maxStock * 100));

        if ($qty <= 0)              $badge = 'Agotado';
        elseif ($qty == 1)          $badge = '¡Último!';
        elseif ($qty <= $STOCK_LOW) $badge = 'Pocas unidades';
        elseif ($qty <= $STOCK_MID) $badge = 'Stock limitado';
        else                        $badge = 'Disponible';
    @endphp

    <div class="rv-card {{ $qty <= 0 ? 'agotado' : '' }}"
         data-nombre="{{ strtolower($producto->nombre) }}"
         data-precio="{{ $producto->precio }}">

        <div class="rv-card-img">
            @if($producto->imagen_url)
                <img class="foto" src="{{ $producto->imagen_url }}"
                     alt="{{ $producto->nombre }}" loading="lazy">
            @else
                <div class="placeholder">
                    <img src="/images/icono-producto.png" alt="">
                    <span>Sin imagen</span>
                </div>
            @endif
            <span class="rv-stock-badge {{ $sc }}">{{ $badge }}</span>
        </div>

        <div class="rv-card-body">
            <div class="rv-card-nombre">{{ $producto->nombre }}</div>
            <div class="rv-card-precio">
                Bs. {{ number_format($producto->precio, 2) }}
                <small>/ unidad</small>
            </div>
            <div class="rv-stock-row {{ $sc }}">
                <span class="rv-stock-dot"></span>
                <span class="rv-stock-text">
                    @if($qty <= 0) Sin stock
                    @elseif($qty == 1) ¡Último disponible!
                    @elseif($qty <= $STOCK_LOW) Solo {{ $qty }} unidades
                    @elseif($qty <= $STOCK_MID) {{ $qty }} unidades disponibles
                    @else {{ $qty }} en stock
                    @endif
                </span>
            </div>
            <div class="rv-stock-bar-wrap">
                <div class="rv-stock-bar {{ $bc }}" style="width: {{ $pct }}%"></div>
            </div>
        </div>

        <div class="rv-card-foot">
            @if($qty > 0)
                {{-- Selector de cantidad + form --}}
                <form action="{{ route('cliente.carrito.agregar', $producto->id_producto) }}"
                      method="POST" class="form-agregar">
                    @csrf
                    <div class="rv-qty-row">
                        <span class="rv-qty-label">Cantidad</span>
                        <div class="rv-qty-ctrl">
                            <button type="button" onclick="cambiarQty(this, -1)">−</button>
                            <input type="number" name="cantidad" value="1" min="1" max="{{ $qty }}" readonly>
                            <button type="button" onclick="cambiarQty(this, 1)">+</button>
                        </div>
                    </div>
                    <button type="submit" class="btn-agregar">
                        <img src="/images/icono-agregar.png" alt="">
                        Agregar al carrito
                    </button>
                </form>
            @else
                <button class="btn-agregar disabled" disabled>Sin stock</button>
            @endif
        </div>

    </div>
    @empty
    <div class="rv-empty-state">
        <img src="/images/icono-producto.png" alt="">
        <p>No hay productos disponibles en este momento.</p>
    </div>
    @endforelse
</div>

<div id="emptySearch" style="display:none; text-align:center; padding:60px 20px; color:var(--ink-muted); font-size:14px;">
    Sin resultados para tu búsqueda o rango de precio.
</div>

@push('scripts')
<script>
const PRECIO_MIN_GLOBAL = {{ $precioMin }};
const PRECIO_MAX_GLOBAL = {{ $precioMax }};

function toggleFiltro() {
    const panel = document.getElementById('filterPanel');
    const btn   = document.getElementById('btnFiltro');
    btn.classList.toggle('active', panel.classList.toggle('open'));
}

function moverSlider() {
    const sMin = document.getElementById('sliderMin');
    const sMax = document.getElementById('sliderMax');
    let vMin = parseInt(sMin.value), vMax = parseInt(sMax.value);
    if (vMin > vMax) { const t = vMin; vMin = vMax; vMax = t; sMin.value = vMin; sMax.value = vMax; }
    document.getElementById('labelMin').textContent = 'Bs. ' + vMin;
    document.getElementById('labelMax').textContent = 'Bs. ' + vMax;
    const total = PRECIO_MAX_GLOBAL - PRECIO_MIN_GLOBAL || 1;
    const pctL = ((vMin - PRECIO_MIN_GLOBAL) / total) * 100;
    const pctR = 100 - ((vMax - PRECIO_MIN_GLOBAL) / total) * 100;
    document.getElementById('sliderRange').style.left  = pctL + '%';
    document.getElementById('sliderRange').style.right = pctR + '%';
    aplicarFiltros();
}

function aplicarFiltros() {
    const texto = document.getElementById('buscador').value.toLowerCase().trim();
    const vMin  = parseInt(document.getElementById('sliderMin').value);
    const vMax  = parseInt(document.getElementById('sliderMax').value);
    const cards = document.querySelectorAll('#catalogoGrid .rv-card');
    let visibles = 0;
    cards.forEach(card => {
        const ok = (card.dataset.nombre || '').includes(texto)
                && parseFloat(card.dataset.precio) >= vMin
                && parseFloat(card.dataset.precio) <= vMax;
        card.style.display = ok ? '' : 'none';
        if (ok) visibles++;
    });
    document.getElementById('resultCount').textContent = visibles + (visibles === 1 ? ' producto' : ' productos');
    document.getElementById('emptySearch').style.display = visibles === 0 ? 'block' : 'none';
}

function limpiarFiltros() {
    document.getElementById('buscador').value  = '';
    document.getElementById('sliderMin').value = PRECIO_MIN_GLOBAL;
    document.getElementById('sliderMax').value = PRECIO_MAX_GLOBAL;
    moverSlider();
}

function cambiarQty(btn, delta) {
    const input = btn.parentElement.querySelector('input');
    const max   = parseInt(input.max);
    const nuevo = Math.min(max, Math.max(1, parseInt(input.value) + delta));
    input.value = nuevo;
}

document.addEventListener('DOMContentLoaded', () => moverSlider());
</script>
@endpush

@endsection