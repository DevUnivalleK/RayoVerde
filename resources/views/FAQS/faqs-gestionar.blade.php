@extends('layout')
@section('title', 'Gestionar FAQs — Rayo Verde')

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
.rv-hero h1 {
    font-family: 'Instrument Serif', serif;
    font-size: 38px; color: #fff; font-weight: 400; letter-spacing: -1px; line-height: 1.1; margin-bottom: 8px;
}
.rv-hero h1 em { font-style: italic; color: var(--green-400); }
.rv-hero p { color: rgba(255,255,255,0.55); font-size: 13px; font-weight: 300; letter-spacing: 0.2px; }

/* Stats bar */
.rv-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
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
    font-size: 30px; color: #fff; letter-spacing: -1px; line-height: 1;
    display: flex; align-items: center; gap: 6px;
}
.rv-stat-lbl {
    font-size: 10px; color: rgba(255,255,255,0.4);
    margin-top: 5px; letter-spacing: 1px; text-transform: uppercase;
}

/* ─── Toolbar ───────────────────────────────────────────── */
.rv-toolbar { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.rv-toolbar-filters { display: flex; gap: 8px; flex-shrink: 0; }

.rv-search {
    flex: 1; display: flex; align-items: center; gap: 10px;
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 0 14px;
    box-shadow: var(--shadow-card); transition: border-color .2s;
}
.rv-search:focus-within { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(79,144,32,0.1); }
.rv-search svg { width: 15px; height: 15px; color: var(--ink-muted); opacity: 0.5; flex-shrink: 0; }
.rv-search input {
    flex: 1; border: none; outline: none;
    font-family: 'Sora', sans-serif; font-size: 13px; color: var(--ink);
    background: transparent; padding: 11px 0;
}
.rv-search input::placeholder { color: var(--ink-muted); }

/* Filtro por categoría */
.rv-filter-select {
    border: 1px solid var(--border); border-radius: var(--radius-sm);
    background: var(--white); color: var(--ink-mid);
    font-family: 'Sora', sans-serif; font-size: 12px; font-weight: 500;
    padding: 0 32px 0 12px; height: 42px; outline: none; cursor: pointer;
    box-shadow: var(--shadow-card); transition: border-color .2s;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237a8f6e' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
}
.rv-filter-select:focus { border-color: var(--green-500); }

/* ─── Table card ────────────────────────────────────────── */
.rv-card {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius-md); overflow: hidden;
    box-shadow: var(--shadow-card); margin-bottom: 16px;
}
.rv-card-head {
    padding: 16px 20px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    background: var(--white);
}
.rv-card-title {
    display: flex; align-items: center; gap: 10px;
    font-size: 14px; font-weight: 600; color: var(--ink); letter-spacing: -0.2px;
}
.rv-card-icon {
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    background: var(--green-100); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.rv-card-icon svg { width: 16px; height: 16px; }
.rv-badge {
    font-size: 10px; background: var(--green-100); color: var(--green-700);
    padding: 3px 10px; border-radius: 20px; font-weight: 600; letter-spacing: 0.3px;
}

/* ─── Table ─────────────────────────────────────────────── */
table { width: 100%; border-collapse: collapse; font-size: 13px; }
thead th {
    padding: 9px 18px; text-align: left;
    font-size: 10px; letter-spacing: 1px; text-transform: uppercase;
    color: var(--ink-muted); background: var(--surface);
    border-bottom: 1px solid var(--border); font-weight: 500; white-space: nowrap;
}
.th-sortable { cursor: pointer; user-select: none; transition: color .15s; }
.th-sortable:hover { color: var(--green-600); }
.sort-icon { display: inline-block; margin-left: 4px; opacity: 0.5; font-size: 9px; }

tbody tr { border-bottom: 1px solid #f0f4eb; transition: background .15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: var(--green-50); }

tbody td { padding: 13px 18px; color: var(--ink); font-size: 13px; vertical-align: middle; }

/* Columna categoría */
td.td-cat { white-space: nowrap; }
.cat-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 600;
    background: var(--green-100); color: var(--green-700);
}
.cat-pill::before {
    content: ''; width: 5px; height: 5px; border-radius: 50%; background: var(--green-500); flex-shrink: 0;
}

/* Columnas de texto largo: truncar con tooltip */
td.td-pregunta, td.td-respuesta {
    max-width: 260px;
}
.td-text-clip {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    color: var(--ink-mid);
    font-size: 12px;
    line-height: 1.5;
}
td.td-pregunta .td-text-clip { color: var(--ink); font-size: 13px; }

/* ─── Action buttons ─────────────────────────────────────── */
.td-actions { display: flex; gap: 6px; align-items: center; }
.btn-icon {
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    border: 1px solid var(--border); background: var(--white);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all .17s ease; flex-shrink: 0; text-decoration: none;
}
.btn-icon svg { width: 14px; height: 14px; }
.btn-icon-edit svg  { color: var(--ink-muted); }
.btn-icon-delete svg { color: var(--ink-muted); }
.btn-icon-edit:hover  { background: var(--green-100); border-color: var(--green-400); transform: scale(1.08); }
.btn-icon-edit:hover svg { color: var(--green-600); }
.btn-icon-delete:hover { background: #fceaea; border-color: #f5c2c2; transform: scale(1.08); }
.btn-icon-delete:hover svg { color: #c0392b; }

/* ─── Bottom buttons ─────────────────────────────────────── */
.rv-btn-row {
    display: flex; justify-content: space-between; align-items: center; gap: 12px;
}
.btn {
    display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px;
    border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
    border: none; cursor: pointer; text-decoration: none;
    transition: all .18s ease; letter-spacing: 0.2px; font-family: 'Sora', sans-serif;
}
.btn svg { width: 14px; height: 14px; flex-shrink: 0; }
.btn-dark  { background: var(--green-600); color: #fff; }
.btn-dark:hover  { background: var(--green-700); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(59,109,17,0.3); }
.btn-ghost { background: transparent; color: var(--ink-mid); border: 1.5px solid var(--border); }
.btn-ghost:hover { background: var(--green-100); border-color: var(--green-500); color: var(--green-700); }

/* ─── Empty state ────────────────────────────────────────── */
.rv-empty {
    padding: 56px 20px; text-align: center;
    color: var(--ink-muted); font-size: 13px;
    display: flex; flex-direction: column; align-items: center; gap: 10px;
}
.rv-empty svg { width: 36px; height: 36px; opacity: 0.3; }
.rv-empty strong { display: block; font-size: 14px; color: var(--ink-mid); font-weight: 600; }

/* ─── Modal overlay ──────────────────────────────────────── */
.rv-modal-overlay {
    position: fixed; inset: 0;
    background: rgba(13,31,5,0.45); backdrop-filter: blur(3px); -webkit-backdrop-filter: blur(3px);
    display: flex; align-items: center; justify-content: center;
    z-index: 9999; opacity: 0; pointer-events: none; transition: opacity .22s ease;
}
.rv-modal-overlay.active { opacity: 1; pointer-events: all; }

.rv-modal {
    background: var(--white); border-radius: var(--radius-lg);
    box-shadow: 0 24px 60px rgba(13,31,5,0.25), 0 4px 16px rgba(0,0,0,0.08);
    width: 380px; max-width: 90vw; overflow: hidden;
    transform: translateY(16px) scale(0.97);
    transition: transform .25s cubic-bezier(.34,1.4,.64,1);
}
.rv-modal-overlay.active .rv-modal { transform: translateY(0) scale(1); }

.rv-modal-header { padding: 22px 24px 0; display: flex; align-items: center; gap: 13px; }
.rv-modal-ico {
    width: 42px; height: 42px; border-radius: 10px; background: #fceaea;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.rv-modal-ico svg { width: 20px; height: 20px; color: #c0392b; }
.rv-modal-title { font-family: 'Instrument Serif', serif; font-size: 20px; color: var(--ink); font-weight: 400; letter-spacing: -0.3px; }

.rv-modal-body { padding: 14px 24px 20px; }
.rv-modal-body p { font-size: 13px; color: var(--ink-mid); line-height: 1.6; }
.rv-modal-body strong { color: var(--ink); font-weight: 600; }

/* Preview de la pregunta dentro del modal */
.rv-modal-preview {
    margin-top: 12px;
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 10px 14px;
    font-size: 12px; color: var(--ink-mid); line-height: 1.5;
    border-left: 3px solid #f5c2c2;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}

.rv-modal-note { margin-top: 10px; font-size: 11px; color: var(--ink-muted) !important; }

.rv-modal-divider { height: 1px; background: var(--border); margin: 0 24px; }
.rv-modal-actions { padding: 16px 24px; display: flex; justify-content: flex-end; gap: 10px; background: var(--surface); }

.btn-modal-cancel {
    background: transparent; color: var(--ink-mid); border: 1.5px solid var(--border);
    padding: 8px 18px; border-radius: var(--radius-sm);
    font-family: 'Sora', sans-serif; font-size: 12px; font-weight: 600;
    cursor: pointer; transition: all .17s;
}
.btn-modal-cancel:hover { background: var(--green-100); border-color: var(--green-400); color: var(--green-700); }

.btn-modal-confirm {
    background: #c0392b; color: #fff; border: none;
    padding: 8px 20px; border-radius: var(--radius-sm);
    font-family: 'Sora', sans-serif; font-size: 12px; font-weight: 600;
    cursor: pointer; transition: all .17s;
    display: flex; align-items: center; gap: 7px;
}
.btn-modal-confirm svg { width: 13px; height: 13px; }
.btn-modal-confirm:hover { background: #a93226; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(192,57,43,0.35); }

/* ─── Responsive ─────────────────────────────────────────── */
@media (max-width: 700px) {
    .rv-hero { padding: 24px 20px 0; border-radius: var(--radius-md); }
    .rv-hero h1 { font-size: 28px; }
    .rv-stats { grid-template-columns: 1fr 1fr; }
    .rv-stats .rv-stat:nth-child(3) { border-right: none; }
    .rv-toolbar { flex-wrap: wrap; }
    .rv-toolbar-filters { width: 100%; }
    .rv-filter-select { flex: 1; }
    td.td-pregunta, td.td-respuesta { max-width: 140px; }
    .rv-btn-row { flex-wrap: wrap; }
    .rv-btn-row .btn { flex: 1; justify-content: center; }
}
</style>
@endpush

@section('content')

@php
$faqs = [
    ['Envíos y Entregas',        '¿Cuánto tarda mi pedido en llegar?',                    'Los pedidos se entregan en un plazo de 24 a 48 horas hábiles según la zona de cobertura.'],
    ['Pagos y Facturación',      '¿Qué métodos de pago aceptan?',                         'Aceptamos efectivo contra entrega, transferencia bancaria y pagos por QR.'],
    ['Rastreo de Pedido',        '¿Cómo puedo rastrear mi pedido?',                       'Puedes rastrear tu pedido ingresando el código de seguimiento en nuestra sección de rastreo.'],
    ['Devoluciones y Cambios',   '¿Cuál es la política de devoluciones?',                 'Tienes hasta 7 días hábiles para solicitar una devolución presentando el comprobante de compra.'],
    ['Cuenta y Registro',        '¿Cómo creo una cuenta?',                               'Haz clic en "Registrarse" e ingresa tu nombre, correo y contraseña para crear tu cuenta.'],
    ['Envíos y Entregas',        '¿Hacen entregas los fines de semana?',                  'Realizamos entregas de lunes a sábado. Los domingos y feriados no hay servicio de entrega.'],
    ['Promociones y Descuentos', '¿Cómo aplico un código de descuento?',                  'Ingresa el código en el campo "Cupón de descuento" al momento de confirmar tu pedido.'],
];

$totalFaqs    = count($faqs);
$categorias   = collect($faqs)->pluck(0)->unique()->values()->toArray();
$totalCats    = count($categorias);
@endphp

{{-- ── HERO ─────────────────────────────────────────────── --}}
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
        <p>Administra las preguntas y respuestas automáticas del asistente virtual</p>
    </div>

    <div class="rv-stats">
        <div class="rv-stat">
            <div class="rv-stat-val">{{ $totalFaqs }}</div>
            <div class="rv-stat-lbl">FAQs registradas</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">{{ $totalCats }}</div>
            <div class="rv-stat-lbl">Categorías</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">
                <svg viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:22px;height:22px;flex-shrink:0;">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                Bot activo
            </div>
            <div class="rv-stat-lbl">Estado del asistente</div>
        </div>
    </div>
</div>

{{-- ── TOOLBAR ──────────────────────────────────────────── --}}
<div class="rv-toolbar">
    <div class="rv-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" id="buscador" placeholder="Buscar por pregunta, categoría o respuesta..." oninput="filtrarTabla()">
    </div>
    <div class="rv-toolbar-filters">
        <select class="rv-filter-select" id="filtroCat" onchange="filtrarTabla()">
            <option value="">Todas las categorías</option>
            @foreach($categorias as $cat)
            <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- ── TABLA ────────────────────────────────────────────── --}}
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
        <span class="rv-badge" id="contador-badge">{{ $totalFaqs }} registros</span>
    </div>

    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th class="th-sortable" onclick="sortTable(0)">Categoría <span class="sort-icon">⇅</span></th>
                    <th class="th-sortable" onclick="sortTable(1)">Pregunta del Usuario <span class="sort-icon">⇅</span></th>
                    <th>Respuesta del Bot</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaBody">
                @foreach($faqs as $i => $f)
                <tr data-index="{{ $i }}"
                    data-categoria="{{ $f[0] }}"
                    data-pregunta="{{ $f[1] }}"
                    data-respuesta="{{ $f[2] }}">

                    <td class="td-cat">
                        <span class="cat-pill">{{ $f[0] }}</span>
                    </td>
                    <td class="td-pregunta">
                        <div class="td-text-clip">{{ $f[1] }}</div>
                    </td>
                    <td class="td-respuesta">
                        <div class="td-text-clip">{{ $f[2] }}</div>
                    </td>
                    <td>
                        <div class="td-actions">
                            <a href="{{ route('admin.faqs.editar') }}" class="btn-icon btn-icon-edit" title="Editar">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            <button class="btn-icon btn-icon-delete" title="Eliminar"
                                onclick="confirmarEliminar('{{ addslashes($f[1]) }}', this)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                    <path d="M10 11v6"/><path d="M14 11v6"/>
                                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="rv-empty" class="rv-empty" style="display:none;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <strong>Sin resultados</strong>
        No se encontraron FAQs con ese criterio de búsqueda.
    </div>
</div>

{{-- ── BOTONES ───────────────────────────────────────────── --}}
<div class="rv-btn-row">
    <a href="{{ route('admin.faqs') }}" class="btn btn-ghost">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Regresar
    </a>
    <a href="{{ route('admin.faqs.crear') }}" class="btn btn-dark">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nueva FAQ
    </a>
</div>

{{-- ── MODAL CONFIRMAR ELIMINAR ─────────────────────────── --}}
<div class="rv-modal-overlay" id="modalEliminar">
    <div class="rv-modal">
        <div class="rv-modal-header">
            <div class="rv-modal-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6"/><path d="M14 11v6"/>
                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
            </div>
            <div class="rv-modal-title">Eliminar FAQ</div>
        </div>
        <div class="rv-modal-body">
            <p>¿Estás seguro que deseas eliminar la siguiente pregunta frecuente?</p>
            <div class="rv-modal-preview" id="modalPregunta"></div>
            <p class="rv-modal-note">Esta acción no se puede deshacer.</p>
        </div>
        <div class="rv-modal-divider"></div>
        <div class="rv-modal-actions">
            <button class="btn-modal-cancel" onclick="cerrarModal()">No, cancelar</button>
            <button class="btn-modal-confirm" onclick="ejecutarEliminar()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                </svg>
                Sí, eliminar
            </button>
        </div>
    </div>
</div>

{{-- ── TOAST ─────────────────────────────────────────────── --}}
<div id="rv-toast" style="
    position:fixed; bottom:28px; right:28px; z-index:99999;
    background:var(--green-700); color:#fff;
    padding:13px 20px; border-radius:var(--radius-sm);
    font-family:'Sora',sans-serif; font-size:13px; font-weight:500;
    display:flex; align-items:center; gap:10px;
    box-shadow:0 8px 24px rgba(26,61,6,0.3);
    opacity:0; transform:translateY(16px);
    transition:opacity .3s ease, transform .3s ease;
    pointer-events:none;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;flex-shrink:0;">
        <polyline points="20 6 9 17 4 12"/>
    </svg>
    <span id="rv-toast-msg">FAQ eliminada.</span>
</div>

@push('scripts')
<script>
// ── Búsqueda + filtro categoría ────────────────────────────
function filtrarTabla() {
    const texto = document.getElementById('buscador').value.toLowerCase();
    const cat   = document.getElementById('filtroCat').value.toLowerCase();
    const filas = document.querySelectorAll('#tablaBody tr');
    let visibles = 0;

    filas.forEach(fila => {
        const textoFila = fila.innerText.toLowerCase();
        const catFila   = (fila.dataset.categoria || '').toLowerCase();
        const okTexto   = !texto || textoFila.includes(texto);
        const okCat     = !cat   || catFila === cat;
        const mostrar   = okTexto && okCat;
        fila.style.display = mostrar ? '' : 'none';
        if (mostrar) visibles++;
    });

    document.getElementById('rv-empty').style.display = visibles === 0 ? 'flex' : 'none';
    document.getElementById('contador-badge').textContent = visibles + ' registros';
}

// ── Ordenar columnas ───────────────────────────────────────
let sortDir = {};
function sortTable(col) {
    sortDir[col] = !sortDir[col];
    const tbody = document.getElementById('tablaBody');
    const filas = Array.from(tbody.querySelectorAll('tr'));
    filas.sort((a, b) => {
        const va = a.cells[col].innerText.trim();
        const vb = b.cells[col].innerText.trim();
        const cmp = va.localeCompare(vb, 'es');
        return sortDir[col] ? cmp : -cmp;
    });
    filas.forEach(f => tbody.appendChild(f));
}

// ── Modal eliminar ─────────────────────────────────────────
let pendienteEliminar = null;

function confirmarEliminar(pregunta, btn) {
    const fila = btn.closest('tr');
    pendienteEliminar = { pregunta, fila };
    document.getElementById('modalPregunta').textContent = pregunta;
    document.getElementById('modalEliminar').classList.add('active');
}

function cerrarModal() {
    document.getElementById('modalEliminar').classList.remove('active');
    pendienteEliminar = null;
}

function ejecutarEliminar() {
    if (!pendienteEliminar) return;
    cerrarModal();

    const fila = pendienteEliminar.fila;
    fila.style.transition = 'opacity .35s ease, transform .35s ease';
    fila.style.opacity    = '0';
    fila.style.transform  = 'translateX(20px)';

    setTimeout(() => {
        fila.remove();
        const total = document.querySelectorAll('#tablaBody tr:not([style*="display: none"])').length;
        document.getElementById('contador-badge').textContent = document.querySelectorAll('#tablaBody tr').length + ' registros';
        const empty = document.getElementById('rv-empty');
        if (document.querySelectorAll('#tablaBody tr').length === 0 && empty) empty.style.display = 'flex';
    }, 370);

    mostrarToast('FAQ eliminada correctamente.');
    pendienteEliminar = null;
}

function mostrarToast(msg) {
    const t = document.getElementById('rv-toast');
    document.getElementById('rv-toast-msg').textContent = msg;
    t.style.opacity   = '1';
    t.style.transform = 'translateY(0)';
    setTimeout(() => {
        t.style.opacity   = '0';
        t.style.transform = 'translateY(16px)';
    }, 3000);
}

// Cerrar modal al click en overlay o Escape
document.getElementById('modalEliminar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') cerrarModal();
});
</script>
@endpush

@endsection