@extends('layout')
@section('title', 'FAQs del Chatbot — Rayo Verde')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap');

/* ─── Reset & base ──────────────────────────────────────── */
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
    --amber-500: #c47c1a;
    --amber-100: #fdf0db;
    --amber-50:  #fef9f0;

    --blue-700:  #185fa5;
    --blue-100:  #e3eef9;

    --purple-700: #5b21b6;
    --purple-100: #ede9fe;

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

/* ─── Hero ──────────────────────────────────────────────── */
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
.rv-brand-name { font-family: 'Instrument Serif', serif; font-size: 20px; color: #fff; letter-spacing: -0.3px; }
.rv-brand-sub  { font-size: 10px; color: rgba(255,255,255,0.45); letter-spacing: 2px; text-transform: uppercase; margin-top: 2px; }
.rv-hero-body  { position: relative; margin-bottom: 32px; }
.rv-hero-eyebrow {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: var(--green-400); margin-bottom: 10px;
}
.rv-hero-eyebrow::before { content: ''; display: block; width: 20px; height: 1.5px; background: var(--green-400); }
.rv-hero h1 { font-family: 'Instrument Serif', serif; font-size: 38px; color: #fff; font-weight: 400; letter-spacing: -1px; line-height: 1.1; margin-bottom: 8px; }
.rv-hero h1 em { font-style: italic; color: var(--green-400); }
.rv-hero p { color: rgba(255,255,255,0.55); font-size: 13px; font-weight: 300; letter-spacing: 0.2px; }

/* Barra de búsqueda dentro del hero */
.rv-hero-search {
    position: relative;
    max-width: 460px;
    margin-top: 18px;
}
.rv-hero-search input {
    width: 100%; padding: 11px 16px 11px 42px;
    border: 1.5px solid rgba(255,255,255,0.2);
    border-radius: var(--radius-sm);
    background: rgba(255,255,255,0.1);
    font-family: 'Sora', sans-serif; font-size: 13px;
    color: #fff; outline: none;
    transition: border-color .2s, background .2s;
    backdrop-filter: blur(4px);
}
.rv-hero-search input::placeholder { color: rgba(255,255,255,0.45); }
.rv-hero-search input:focus {
    border-color: var(--green-400);
    background: rgba(255,255,255,0.15);
}
.rv-hero-search-icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    pointer-events: none; color: rgba(255,255,255,0.45);
}
.rv-hero-search-icon svg { width: 16px; height: 16px; display: block; }
.rv-hero-search-clear {
    position: absolute; right: 11px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; padding: 3px;
    color: rgba(255,255,255,0.45); display: none;
    transition: color .15s;
}
.rv-hero-search-clear:hover { color: #fff; }
.rv-hero-search-clear svg { width: 14px; height: 14px; display: block; }
.rv-hero-search-clear.show { display: flex; }

/* Stats bar */
.rv-stats {
    display: grid; grid-template-columns: repeat(4, 1fr);
    border-top: 1px solid rgba(255,255,255,0.1); position: relative;
}
.rv-stat { padding: 20px 24px; border-right: 1px solid rgba(255,255,255,0.08); transition: background .2s; }
.rv-stat:last-child { border-right: none; }
.rv-stat:hover { background: rgba(255,255,255,0.05); }
.rv-stat-val {
    font-family: 'Instrument Serif', serif; font-size: 30px; color: #fff;
    letter-spacing: -1px; line-height: 1; display: flex; align-items: center; gap: 6px;
}
.rv-stat-lbl { font-size: 10px; color: rgba(255,255,255,0.4); margin-top: 5px; letter-spacing: 1px; text-transform: uppercase; }
.dot { display: inline-block; width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.dot-on  { background: #8de88d; box-shadow: 0 0 6px rgba(141,232,141,0.6); }

/* ─── Main grid ─────────────────────────────────────────── */
.rv-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }

/* ─── Cards ─────────────────────────────────────────────── */
.rv-card {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius-md); overflow: hidden;
    box-shadow: var(--shadow-card); display: flex; flex-direction: column;
}
.rv-card-head {
    padding: 16px 20px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    background: var(--white);
}
.rv-card-title { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 600; color: var(--ink); letter-spacing: -0.2px; }
.rv-card-icon {
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    background: var(--green-100); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.rv-card-icon svg { width: 16px; height: 16px; }
.rv-badge { font-size: 10px; background: var(--green-100); color: var(--green-700); padding: 3px 10px; border-radius: 20px; font-weight: 600; letter-spacing: 0.3px; }

/* ─── Tables ─────────────────────────────────────────────── */
table { width: 100%; border-collapse: collapse; font-size: 13px; }
thead th {
    padding: 9px 18px; text-align: left; font-size: 10px; letter-spacing: 1px;
    text-transform: uppercase; color: var(--ink-muted);
    background: var(--surface); border-bottom: 1px solid var(--border); font-weight: 500;
}
tbody tr { border-bottom: 1px solid #f0f4eb; transition: background .15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: var(--green-50); }
tbody td { padding: 11px 18px; color: var(--ink); font-size: 13px; vertical-align: middle; }
tbody td.muted { color: var(--ink-muted); font-size: 12px; font-weight: 300; }

/* Texto largo truncado */
.td-clip {
    max-width: 220px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    font-size: 12px; color: var(--ink-mid);
}
.td-clip-bold { font-size: 13px; color: var(--ink); font-weight: 500; }

/* Pill de categoría */
.cat-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 9px; border-radius: 20px;
    font-size: 10px; font-weight: 600; white-space: nowrap;
    background: var(--green-100); color: var(--green-700);
}
.cat-pill::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: var(--green-500); flex-shrink: 0; }

/* ─── Empty search state ─────────────────────────────────── */
.rv-empty-search {
    padding: 28px 20px; text-align: center;
    color: var(--ink-muted); font-size: 12px; display: none;
}
.rv-empty-search svg { width: 28px; height: 28px; opacity: 0.25; margin-bottom: 6px; }
.rv-empty-search strong { display: block; font-size: 13px; color: var(--ink-mid); margin-bottom: 3px; }

/* ─── Card footer ─────────────────────────────────────────── */
.rv-card-foot {
    padding: 14px 20px; border-top: 1px solid var(--border);
    display: flex; justify-content: flex-end; margin-top: auto; background: var(--surface);
}

/* ─── Buttons ─────────────────────────────────────────────── */
.btn {
    display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px;
    border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
    border: none; cursor: pointer; text-decoration: none;
    transition: all .18s ease; letter-spacing: 0.2px; font-family: 'Sora', sans-serif;
}
.btn svg { width: 14px; height: 14px; flex-shrink: 0; }
.btn-dark  { background: var(--green-600); color: #fff; }
.btn-dark:hover  { background: var(--green-700); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(59,109,17,0.3); }
.btn-ghost { background: transparent; color: var(--green-600); border: 1.5px solid var(--border); }
.btn-ghost:hover { background: var(--green-100); border-color: var(--green-500); }

/* ─── Bottom row ──────────────────────────────────────────── */
.rv-bottom { display: grid; grid-template-columns: 1fr 320px; gap: 16px; }

/* ─── Distribución por categoría ─────────────────────────── */
.rv-cat-card {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-card);
}
.rv-cat-body { padding: 8px 20px 20px; }

.cat-row { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid #f0f4eb; }
.cat-row:last-child { border-bottom: none; }

.cat-row-ico {
    width: 30px; height: 30px; border-radius: 8px;
    background: var(--green-100); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.cat-row-ico svg { width: 14px; height: 14px; color: var(--green-600); }

.cat-row-info { flex: 1; min-width: 0; }
.cat-row-name { font-size: 12px; font-weight: 500; color: var(--ink); margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.bar-wrap { height: 5px; background: var(--border); border-radius: 3px; overflow: hidden; }
.bar { height: 100%; border-radius: 3px; background: linear-gradient(90deg, var(--green-600), var(--green-400)); transition: width .5s ease; }

.cat-row-meta { text-align: right; flex-shrink: 0; }
.cat-row-count { font-size: 13px; font-weight: 700; color: var(--green-700); font-variant-numeric: tabular-nums; }
.cat-row-label { font-size: 10px; color: var(--ink-muted); letter-spacing: 0.5px; }

/* ─── Accesos rápidos ─────────────────────────────────────── */
.rv-quick {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-card);
}
.rv-quick-head {
    padding: 14px 18px; border-bottom: 1px solid var(--border);
    font-size: 12px; font-weight: 600; color: var(--ink-muted);
    letter-spacing: 1px; text-transform: uppercase; background: var(--surface);
}
.quick-item {
    display: flex; align-items: center; gap: 13px; padding: 14px 18px;
    border-bottom: 1px solid #f0f4eb; text-decoration: none; transition: background .15s;
}
.quick-item:last-child { border-bottom: none; }
.quick-item:hover { background: var(--green-50); }
.quick-ico {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.quick-ico svg { width: 18px; height: 18px; }
.ico-green  { background: var(--green-100); }
.ico-green svg  { color: var(--green-600); }
.ico-amber  { background: var(--amber-100); }
.ico-amber svg  { color: var(--amber-700); }
.ico-blue   { background: var(--blue-100); }
.ico-blue svg   { color: var(--blue-700); }
.ico-purple { background: var(--purple-100); }
.ico-purple svg { color: var(--purple-700); }
.quick-label { font-size: 13px; font-weight: 600; color: var(--ink); letter-spacing: -0.1px; }
.quick-sub   { font-size: 11px; color: var(--ink-muted); margin-top: 2px; font-weight: 300; }
.quick-arrow { margin-left: auto; color: var(--border); font-size: 20px; line-height: 1; transition: color .15s, transform .15s; }
.quick-item:hover .quick-arrow { color: var(--green-500); transform: translateX(2px); }

/* ─── Highlight de búsqueda ──────────────────────────────── */
mark { background: rgba(107,184,58,0.2); color: var(--green-700); border-radius: 2px; padding: 0 1px; font-style: normal; }

/* ─── Responsive ──────────────────────────────────────────── */
@media (max-width: 900px) {
    .rv-grid   { grid-template-columns: 1fr; }
    .rv-bottom { grid-template-columns: 1fr; }
    .rv-stats  { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .rv-hero   { padding: 24px 20px 0; border-radius: var(--radius-md); }
    .rv-hero h1 { font-size: 28px; }
    .rv-stats  { grid-template-columns: 1fr 1fr; }
    .rv-hero-search { max-width: 100%; }
}
</style>
@endpush

@section('content')

@php
$faqs = [
    ['Envíos y Entregas',        '¿Cuánto tarda mi pedido en llegar?',              'Los pedidos se entregan en un plazo de 24 a 48 horas hábiles según la zona de cobertura.'],
    ['Pagos y Facturación',      '¿Qué métodos de pago aceptan?',                   'Aceptamos efectivo contra entrega, transferencia bancaria y pagos por QR.'],
    ['Rastreo de Pedido',        '¿Cómo puedo rastrear mi pedido?',                 'Puedes rastrear tu pedido ingresando el código de seguimiento en nuestra sección de rastreo.'],
    ['Devoluciones y Cambios',   '¿Cuál es la política de devoluciones?',           'Tienes hasta 7 días hábiles para solicitar una devolución presentando el comprobante de compra.'],
    ['Cuenta y Registro',        '¿Cómo creo una cuenta?',                         'Haz clic en "Registrarse" e ingresa tu nombre, correo y contraseña para crear tu cuenta.'],
    ['Envíos y Entregas',        '¿Hacen entregas los fines de semana?',            'Realizamos entregas de lunes a sábado. Los domingos y feriados no hay servicio.'],
    ['Promociones y Descuentos', '¿Cómo aplico un código de descuento?',            'Ingresa el código en el campo "Cupón de descuento" al confirmar tu pedido.'],
    ['Soporte Técnico',          '¿Qué hago si la app no carga?',                   'Intenta cerrar y volver a abrir la aplicación. Si el problema persiste, contáctanos.'],
];

// Agrupar por categoría para la distribución
$porCategoria = collect($faqs)->groupBy(0)->map->count()->sortDesc();
$maxCat       = $porCategoria->max();
$totalFaqs    = count($faqs);
$totalCats    = $porCategoria->count();
$catMasFrecuente = $porCategoria->keys()->first();
@endphp

{{-- ── HERO ──────────────────────────────────────────────── --}}
<div class="rv-hero">
    <div class="rv-hero-top">
        <div class="rv-logo"><img src="/images/logo.png" alt="Rayo Verde"></div>
        <div>
            <div class="rv-brand-name">Rayo Verde</div>
            <div class="rv-brand-sub">Panel Administrativo</div>
        </div>
    </div>

    <div class="rv-hero-body">
        <div class="rv-hero-eyebrow">Preguntas Frecuentes</div>
        <h1>FAQs del <em>Chatbot</em></h1>
        <p>Vista general de preguntas, categorías y distribución del asistente virtual</p>

        {{-- Barra de búsqueda --}}
        <div class="rv-hero-search">
            <div class="rv-hero-search-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </div>
            <input type="text" id="buscadorHero"
                placeholder="Buscar por pregunta, categoría o respuesta..."
                oninput="buscarFaqs(this.value)" autocomplete="off">
            <button class="rv-hero-search-clear" id="btnClearSearch" onclick="limpiarBusqueda()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="rv-stats">
        <div class="rv-stat">
            <div class="rv-stat-val" id="stat-total">{{ $totalFaqs }}</div>
            <div class="rv-stat-lbl">FAQs registradas</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">{{ $totalCats }}</div>
            <div class="rv-stat-lbl">Categorías activas</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val"><span class="dot dot-on"></span>&nbsp;On</div>
            <div class="rv-stat-lbl">Bot en línea</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val" style="font-size:18px; align-items:flex-start;">{{ Str::limit($catMasFrecuente, 14) }}</div>
            <div class="rv-stat-lbl">Categoría más frecuente</div>
        </div>
    </div>
</div>

{{-- ── GRID TABLAS ─────────────────────────────────────────── --}}
<div class="rv-grid">

    {{-- Preguntas recientes --}}
    <div class="rv-card">
        <div class="rv-card-head">
            <div class="rv-card-title">
                <div class="rv-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--green-600)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                Preguntas frecuentes
            </div>
            <span class="rv-badge" id="badge-faqs">{{ $totalFaqs }} registros</span>
        </div>

        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>Pregunta del Usuario</th>
                        <th>Respuesta del Bot</th>
                    </tr>
                </thead>
                <tbody id="tablaFaqs">
                    @foreach($faqs as $f)
                    <tr data-cat="{{ $f[0] }}" data-preg="{{ $f[1] }}" data-resp="{{ $f[2] }}">
                        <td><span class="cat-pill">{{ $f[0] }}</span></td>
                        <td><div class="td-clip td-clip-bold">{{ $f[1] }}</div></td>
                        <td><div class="td-clip">{{ $f[2] }}</div></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="rv-empty-search" id="emptySearch">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <strong>Sin resultados</strong>
            No se encontraron FAQs con ese criterio.
        </div>

        <div class="rv-card-foot">
            <a href="{{ route('admin.faqs.gestionar') }}" class="btn btn-dark">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Gestionar FAQs
            </a>
        </div>
    </div>

    {{-- Últimas FAQs añadidas --}}
    <div class="rv-card">
        <div class="rv-card-head">
            <div class="rv-card-title">
                <div class="rv-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--green-600)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                Añadidas recientemente
            </div>
            <span class="rv-badge">Últimas 5</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Pregunta</th>
                </tr>
            </thead>
            <tbody>
                @foreach(array_slice($faqs, -5) as $f)
                <tr>
                    <td><span class="cat-pill">{{ $f[0] }}</span></td>
                    <td><div class="td-clip">{{ $f[1] }}</div></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="rv-card-foot">
            <a href="{{ route('admin.faqs.crear') }}" class="btn btn-ghost">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Nueva FAQ
            </a>
        </div>
    </div>

</div>

{{-- ── FILA INFERIOR ────────────────────────────────────────── --}}
<div class="rv-bottom">

    {{-- Distribución por categoría --}}
    <div class="rv-cat-card">
        <div class="rv-card-head">
            <div class="rv-card-title">
                <div class="rv-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--green-600)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                    </svg>
                </div>
                Distribución por categoría
            </div>
            <span class="rv-badge">{{ $totalCats }} categorías</span>
        </div>
        <div class="rv-cat-body">
            @foreach($porCategoria as $cat => $count)
            @php $pct = $maxCat > 0 ? round($count / $maxCat * 100) : 0; @endphp
            <div class="cat-row">
                <div class="cat-row-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                <div class="cat-row-info">
                    <div class="cat-row-name">{{ $cat }}</div>
                    <div class="bar-wrap"><div class="bar" style="width:{{ $pct }}%"></div></div>
                </div>
                <div class="cat-row-meta">
                    <div class="cat-row-count">{{ $count }}</div>
                    <div class="cat-row-label">{{ $count === 1 ? 'pregunta' : 'preguntas' }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Accesos rápidos --}}
    <div class="rv-quick">
        <div class="rv-quick-head">Accesos rápidos</div>

        <a href="{{ route('admin.faqs.gestionar') }}" class="quick-item">
            <div class="quick-ico ico-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
            </div>
            <div>
                <div class="quick-label">Gestionar FAQs</div>
                <div class="quick-sub">Ver, editar y eliminar preguntas</div>
            </div>
            <span class="quick-arrow">›</span>
        </a>

        <a href="{{ route('admin.faqs.crear') }}" class="quick-item">
            <div class="quick-ico ico-amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
            </div>
            <div>
                <div class="quick-label">Nueva FAQ</div>
                <div class="quick-sub">Registrar pregunta y respuesta</div>
            </div>
            <span class="quick-arrow">›</span>
        </a>

        <a href="{{ route('admin.envios.gestionar') }}" class="quick-item">
            <div class="quick-ico ico-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
            </div>
            <div>
                <div class="quick-label">Gestionar envíos</div>
                <div class="quick-sub">Tarifas y zonas de cobertura</div>
            </div>
            <span class="quick-arrow">›</span>
        </a>

        <a href="#" class="quick-item">
            <div class="quick-ico ico-purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <div>
                <div class="quick-label">Panel principal</div>
                <div class="quick-sub">Volver al inicio del admin</div>
            </div>
            <span class="quick-arrow">›</span>
        </a>
    </div>

</div>

@push('scripts')
<script>
// ── Búsqueda en tiempo real ──────────────────────────────────
function buscarFaqs(query) {
    const q     = query.trim().toLowerCase();
    const filas = document.querySelectorAll('#tablaFaqs tr');
    const btnX  = document.getElementById('btnClearSearch');
    let visibles = 0;

    btnX.classList.toggle('show', q.length > 0);

    filas.forEach(fila => {
        const cat  = fila.dataset.cat  || '';
        const preg = fila.dataset.preg || '';
        const resp = fila.dataset.resp || '';
        const todo = (cat + ' ' + preg + ' ' + resp).toLowerCase();
        const ok   = !q || todo.includes(q);

        fila.style.display = ok ? '' : 'none';

        if (ok && q) {
            // Resaltar coincidencias
            fila.querySelectorAll('.cat-pill, .td-clip').forEach(el => {
                const orig = el.dataset.orig || el.textContent;
                el.dataset.orig = orig;
                const regex = new RegExp(`(${q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                el.innerHTML = orig.replace(regex, '<mark>$1</mark>');
            });
        } else {
            // Quitar highlights
            fila.querySelectorAll('.cat-pill, .td-clip').forEach(el => {
                if (el.dataset.orig) el.textContent = el.dataset.orig;
            });
        }

        if (ok) visibles++;
    });

    document.getElementById('emptySearch').style.display = visibles === 0 ? 'flex' : 'none';
    document.getElementById('badge-faqs').textContent = visibles + ' registros';
    document.getElementById('stat-total').textContent  = visibles;
}

function limpiarBusqueda() {
    const input = document.getElementById('buscadorHero');
    input.value = '';
    buscarFaqs('');
    input.focus();
}
</script>
@endpush

@endsection