@extends('layout')
@section('title', 'Envíos y Regiones — Rayo Verde')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap');

/* ─── Reset & base ─────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --green-900: #1a3d06;
    --green-700: #2c5a0e;
    --green-600: #3b6d11;
    --green-500: #4f9020;
    --green-400: #6bb83a;
    --green-100: #edf5e1;
    --green-50:  #f4faea;

    --amber-700: #633806;
    --amber-100: #fdf0db;

    --blue-700:  #185fa5;
    --blue-100:  #e3eef9;

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

body {
    font-family: 'Sora', sans-serif;
    background: var(--surface);
    color: var(--ink);
    line-height: 1.5;
}

/* ─── Hero ─────────────────────────────────────────── */
.rv-hero {
    position: relative;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 60%, var(--green-600) 100%);
    border-radius: var(--radius-lg);
    padding: 32px 36px 0;
    margin-bottom: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-hero);
}

.rv-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 60% 80% at 90% -10%, rgba(107,184,58,0.18) 0%, transparent 60%),
        radial-gradient(ellipse 40% 60% at -5% 100%, rgba(255,255,255,0.05) 0%, transparent 50%);
    pointer-events: none;
}

.rv-hero::after {
    content: '';
    position: absolute;
    right: -60px; bottom: -60px;
    width: 260px; height: 260px;
    border-radius: 50%;
    border: 40px solid rgba(255,255,255,0.04);
}

.rv-hero-top {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 24px;
    position: relative;
}

.rv-logo {
    width: 48px; height: 48px;
    border-radius: 12px;
    background: rgba(255,255,255,0.12);
    border: 1.5px solid rgba(255,255,255,0.25);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}
.rv-logo img { width: 100%; height: 100%; object-fit: contain; }

.rv-brand-name {
    font-family: 'Instrument Serif', serif;
    font-size: 20px;
    color: #fff;
    letter-spacing: -0.3px;
}
.rv-brand-sub {
    font-size: 10px;
    color: rgba(255,255,255,0.45);
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-top: 2px;
}

.rv-hero-body { position: relative; margin-bottom: 32px; }

.rv-hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--green-400);
    margin-bottom: 10px;
}
.rv-hero-eyebrow::before {
    content: '';
    display: block;
    width: 20px; height: 1.5px;
    background: var(--green-400);
}

.rv-hero h1 {
    font-family: 'Instrument Serif', serif;
    font-size: 38px;
    color: #fff;
    font-weight: 400;
    letter-spacing: -1px;
    line-height: 1.1;
    margin-bottom: 8px;
}
.rv-hero h1 em {
    font-style: italic;
    color: var(--green-400);
}
.rv-hero p {
    color: rgba(255,255,255,0.55);
    font-size: 13px;
    font-weight: 300;
    letter-spacing: 0.2px;
}

/* Stats bar */
.rv-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    border-top: 1px solid rgba(255,255,255,0.1);
    position: relative;
}

.rv-stat {
    padding: 20px 24px;
    border-right: 1px solid rgba(255,255,255,0.08);
    transition: background .2s;
}
.rv-stat:last-child { border-right: none; }
.rv-stat:hover { background: rgba(255,255,255,0.05); }

.rv-stat-val {
    font-family: 'Instrument Serif', serif;
    font-size: 30px;
    color: #fff;
    letter-spacing: -1px;
    line-height: 1;
    display: flex;
    align-items: center;
    gap: 6px;
}
.rv-stat-lbl {
    font-size: 10px;
    color: rgba(255,255,255,0.4);
    margin-top: 5px;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.dot {
    display: inline-block;
    width: 7px; height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
}
.dot-on  { background: #8de88d; box-shadow: 0 0 6px rgba(141,232,141,0.6); }
.dot-off { background: #ff9e9e; box-shadow: 0 0 6px rgba(255,158,158,0.5); }

/* ─── Main grid ─────────────────────────────────────── */
.rv-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}

/* ─── Cards ─────────────────────────────────────────── */
.rv-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: var(--shadow-card);
    display: flex;
    flex-direction: column;
}

.rv-card-head {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--white);
}

.rv-card-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 600;
    color: var(--ink);
    letter-spacing: -0.2px;
}

.rv-card-icon {
    width: 32px; height: 32px;
    border-radius: var(--radius-sm);
    background: var(--green-100);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}
.rv-card-icon img { width: 18px; height: 18px; object-fit: contain; }

.rv-badge {
    font-size: 10px;
    background: var(--green-100);
    color: var(--green-700);
    padding: 3px 10px;
    border-radius: 20px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

/* ─── Tables ─────────────────────────────────────────── */
table { width: 100%; border-collapse: collapse; font-size: 13px; }

thead th {
    padding: 9px 18px;
    text-align: left;
    font-size: 10px;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--ink-muted);
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    font-weight: 500;
}

tbody tr {
    border-bottom: 1px solid #f0f4eb;
    transition: background .15s;
}
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: var(--green-50); }

tbody td {
    padding: 12px 18px;
    color: var(--ink);
    font-size: 13px;
    vertical-align: middle;
}
tbody td.muted {
    color: var(--ink-muted);
    font-size: 12px;
    font-weight: 300;
}

.pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.2px;
}
.pill-on  { background: var(--green-100); color: var(--green-700); }
.pill-off { background: #fceaea; color: #7a1f1f; }
.pill-on::before  { content: ''; width: 5px; height: 5px; border-radius: 50%; background: var(--green-500); }
.pill-off::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: #d94a4a; }

.price {
    font-weight: 600;
    color: var(--green-600);
    font-variant-numeric: tabular-nums;
}

/* ─── Card footer ─────────────────────────────────────── */
.rv-card-foot {
    padding: 14px 20px;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: flex-end;
    margin-top: auto;
    background: var(--surface);
}

/* ─── Buttons ─────────────────────────────────────────── */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 18px;
    border-radius: var(--radius-sm);
    font-size: 12px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all .18s ease;
    letter-spacing: 0.2px;
}
.btn img { width: 14px; height: 14px; object-fit: contain; }

.btn-dark {
    background: var(--green-600);
    color: #fff;
}
.btn-dark:hover {
    background: var(--green-700);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(59,109,17,0.3);
}

.btn-ghost {
    background: transparent;
    color: var(--green-600);
    border: 1.5px solid var(--border);
}
.btn-ghost:hover {
    background: var(--green-100);
    border-color: var(--green-500);
}

/* ─── Bottom row ──────────────────────────────────────── */
.rv-bottom {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 16px;
}

/* Distribution card */
.rv-map-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: var(--shadow-card);
}
.rv-map-body { padding: 8px 20px 20px; }

.city-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 0;
    border-bottom: 1px solid #f0f4eb;
}
.city-row:last-child { border-bottom: none; }

.city-icon {
    width: 28px; height: 28px;
    border-radius: 6px;
    background: var(--green-100);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.city-icon img { width: 15px; height: 15px; object-fit: contain; }

.city-info { flex: 1; min-width: 0; }
.city-name {
    font-size: 13px;
    font-weight: 500;
    color: var(--ink);
    margin-bottom: 5px;
}
.bar-wrap {
    height: 5px;
    background: var(--border);
    border-radius: 3px;
    overflow: hidden;
}
.bar {
    height: 100%;
    border-radius: 3px;
    background: linear-gradient(90deg, var(--green-600), var(--green-400));
    transition: width .4s ease;
}

.city-meta {
    text-align: right;
    flex-shrink: 0;
}
.city-count {
    font-size: 13px;
    font-weight: 700;
    color: var(--green-700);
    font-variant-numeric: tabular-nums;
}
.city-label {
    font-size: 10px;
    color: var(--ink-muted);
    letter-spacing: 0.5px;
}

/* Quick access */
.rv-quick {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: var(--shadow-card);
}

.rv-quick-head {
    padding: 14px 18px;
    border-bottom: 1px solid var(--border);
    font-size: 12px;
    font-weight: 600;
    color: var(--ink-muted);
    letter-spacing: 1px;
    text-transform: uppercase;
    background: var(--surface);
}

.quick-item {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 14px 18px;
    border-bottom: 1px solid #f0f4eb;
    text-decoration: none;
    transition: background .15s;
    cursor: pointer;
}
.quick-item:last-child { border-bottom: none; }
.quick-item:hover { background: var(--green-50); }

.quick-ico {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}
.quick-ico img { width: 20px; height: 20px; object-fit: contain; }

.ico-green { background: var(--green-100); }
.ico-amber { background: var(--amber-100); }
.ico-blue  { background: var(--blue-100); }

.quick-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--ink);
    letter-spacing: -0.1px;
}
.quick-sub {
    font-size: 11px;
    color: var(--ink-muted);
    margin-top: 2px;
    font-weight: 300;
}
.quick-arrow {
    margin-left: auto;
    color: var(--border);
    font-size: 20px;
    line-height: 1;
    transition: color .15s, transform .15s;
}
.quick-item:hover .quick-arrow {
    color: var(--green-500);
    transform: translateX(2px);
}
</style>
@endpush

@section('content')

@php
$envios = [
    ['Zona Sur',        'La Paz',     '15.00', 'Activo'],
    ['Centro',          'La Paz',     '10.00', 'Activo'],
    ['El Alto',         'El Alto',    '12.00', 'Activo'],
    ['Cercado',         'Cochabamba', '20.00', 'Activo'],
    ['Distrito 1',      'Santa Cruz', '25.00', 'Inactivo'],
    ['Provincia Aroma', 'La Paz',     '30.00', 'Activo'],
];
$activas   = collect($envios)->where(3, 'Activo')->count();
$inactivas = count($envios) - $activas;
$promedio  = collect($envios)->avg(fn($e) => (float) $e[2]);
@endphp

{{-- ── HERO ───────────────────────────────────────── --}}
<div class="rv-hero">
    <div class="rv-hero-top">
        <div class="rv-logo">
            <img src="/images/logo.png" alt="Rayo Verde">
        </div>
        <div>
            <div class="rv-brand-name">Rayo Verde</div>
            <div class="rv-brand-sub">Panel Administrativo</div>
        </div>
    </div>

    <div class="rv-hero-body">
        <div class="rv-hero-eyebrow">Logística</div>
        <h1>Envíos y <em>Regiones</em></h1>
        <p>Vista general de costos, zonas activas y distribución geográfica</p>
    </div>

    <div class="rv-stats">
        <div class="rv-stat">
            <div class="rv-stat-val">{{ count($envios) }}</div>
            <div class="rv-stat-lbl">Envíos registrados</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val"><span class="dot dot-on"></span>{{ $activas }}</div>
            <div class="rv-stat-lbl">Envíos activos</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val"><span class="dot dot-off"></span>{{ $inactivas }}</div>
            <div class="rv-stat-lbl">Envíos inactivos</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">Bs. {{ number_format($promedio, 1) }}</div>
            <div class="rv-stat-lbl">Costo promedio envío</div>
        </div>
    </div>
</div>

{{-- ── TABLAS ──────────────────────────────────────── --}}
<div class="rv-grid">

    {{-- Tarifas --}}
    <div class="rv-card">
        <div class="rv-card-head">
            <div class="rv-card-title">
                <div class="rv-card-icon">
                    <img src="/images/icono-envio.png" alt="">
                </div>
                Envíos registrados
            </div>
            <span class="rv-badge">{{ count($envios) }} registros</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Región</th>
                    <th>Ciudad</th>
                    <th>Costo (Bs.)</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($envios as $e)
                <tr>
                    <td>{{ $e[0] }}</td>
                    <td class="muted">{{ $e[1] }}</td>
                    <td class="price">{{ $e[2] }}</td>
                    <td>
                        <span class="pill {{ $e[3] === 'Activo' ? 'pill-on' : 'pill-off' }}">
                            {{ $e[3] }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="rv-card-foot">
            <a href="{{ route('admin.envios.gestionar') }}" class="btn btn-dark">
                <img src="/images/icono-gestionar.png" alt="">
                Gestionar envíos
            </a>
        </div>
    </div>

    {{-- Regiones --}}
    <div class="rv-card">
        <div class="rv-card-head">
            <div class="rv-card-title">
                <div class="rv-card-icon">
                    <img src="/images/icono-region.png" alt="">
                </div>
                Regiones registradas
            </div>
            @php
            $regiones = [
                ['Zona Sur',        'La Paz'],
                ['Centro',          'La Paz'],
                ['El Alto',         'El Alto'],
                ['Cota Cota',       'La Paz'],
                ['Achumani',        'La Paz'],
                ['Cercado',         'Cochabamba'],
                ['Equipetrol',      'Santa Cruz'],
                ['Plan 3000',       'Santa Cruz'],
            ];
            @endphp
            <span class="rv-badge">{{ count($regiones) }} regiones</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nombre de la Región</th>
                    <th>Ciudad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($regiones as $r)
                <tr>
                    <td>{{ $r[0] }}</td>
                    <td class="muted">{{ $r[1] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="rv-card-foot">
            <a href="{{ route('admin.regiones.gestionar') }}" class="btn btn-ghost">
                <img src="/images/icono-agregar.png" alt="">
                Gestionar regiones
            </a>
        </div>
    </div>

</div>

{{-- ── FILA INFERIOR ───────────────────────────────── --}}
<div class="rv-bottom">

    {{-- Distribución por ciudad --}}
    <div class="rv-map-card">
        <div class="rv-card-head">
            <div class="rv-card-title">
                <div class="rv-card-icon">
                    <img src="/images/icono-ciudad.png" alt="">
                </div>
                Distribución por ciudad
            </div>
        </div>
        <div class="rv-map-body">

            <div class="city-row">
                <div class="city-icon"><img src="/images/icono-ciudad.png" alt=""></div>
                <div class="city-info">
                    <div class="city-name">La Paz</div>
                    <div class="bar-wrap"><div class="bar" style="width:75%"></div></div>
                </div>
                <div class="city-meta">
                    <div class="city-count">3</div>
                    <div class="city-label">zonas</div>
                </div>
            </div>

            <div class="city-row">
                <div class="city-icon"><img src="/images/icono-ciudad.png" alt=""></div>
                <div class="city-info">
                    <div class="city-name">El Alto</div>
                    <div class="bar-wrap"><div class="bar" style="width:25%"></div></div>
                </div>
                <div class="city-meta">
                    <div class="city-count">1</div>
                    <div class="city-label">zona</div>
                </div>
            </div>

            <div class="city-row">
                <div class="city-icon"><img src="/images/icono-ciudad.png" alt=""></div>
                <div class="city-info">
                    <div class="city-name">Cochabamba</div>
                    <div class="bar-wrap"><div class="bar" style="width:25%"></div></div>
                </div>
                <div class="city-meta">
                    <div class="city-count">1</div>
                    <div class="city-label">zona</div>
                </div>
            </div>

            <div class="city-row">
                <div class="city-icon"><img src="/images/icono-ciudad.png" alt=""></div>
                <div class="city-info">
                    <div class="city-name">Santa Cruz</div>
                    <div class="bar-wrap"><div class="bar" style="width:25%"></div></div>
                </div>
                <div class="city-meta">
                    <div class="city-count">1</div>
                    <div class="city-label">zona</div>
                </div>
            </div>

        </div>
    </div>

    {{-- Accesos rápidos --}}
    <div class="rv-quick">
        <div class="rv-quick-head">Accesos rápidos</div>

        <a href="{{ route('admin.envios.gestionar') }}" class="quick-item">
            <div class="quick-ico ico-green">
                <img src="/images/icono-envio.png" alt="">
            </div>
            <div>
                <div class="quick-label">Gestionar envíos</div>
                <div class="quick-sub">Ver, editar y eliminar tarifas</div>
            </div>
            <span class="quick-arrow">›</span>
        </a>

        <a href="{{ route('admin.faqs') }}" class="quick-item">
            <div class="quick-ico ico-amber">
                <img src="/images/icono-faq.png" alt="">
            </div>
            <div>
                <div class="quick-label">Preguntas frecuentes</div>
                <div class="quick-sub">Gestionar respuestas del bot</div>
            </div>
            <span class="quick-arrow">›</span>
        </a>

        <a href="#" class="quick-item">
            <div class="quick-ico ico-blue">
                <img src="/images/icono-producto.png" alt="">
            </div>
            <div>
                <div class="quick-label">Productos</div>
                <div class="quick-sub">Aceites y disponibilidad</div>
            </div>
            <span class="quick-arrow">›</span>
        </a>
    </div>

</div>

@endsection