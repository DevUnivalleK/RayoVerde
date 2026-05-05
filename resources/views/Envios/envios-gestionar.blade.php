@extends('layout')
@section('title', 'Gestionar Envíos — Rayo Verde')

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

    --amber-700: #633806;
    --amber-100: #fdf0db;
    --amber-500: #c47c1a;

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
.dot { display: inline-block; width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.dot-on  { background: #8de88d; box-shadow: 0 0 6px rgba(141,232,141,0.6); }
.dot-off { background: #ff9e9e; box-shadow: 0 0 6px rgba(255,158,158,0.5); }

/* ─── Search & actions bar ─────────────────────────── */
.rv-toolbar {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.rv-search {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 0 14px;
    box-shadow: var(--shadow-card);
    transition: border-color .2s;
}
.rv-search:focus-within {
    border-color: var(--green-500);
    box-shadow: 0 0 0 3px rgba(79,144,32,0.1);
}
.rv-search-icon {
    width: 16px; height: 16px;
    object-fit: contain;
    opacity: 0.45;
    flex-shrink: 0;
}
.rv-search input {
    flex: 1;
    border: none;
    outline: none;
    font-family: 'Sora', sans-serif;
    font-size: 13px;
    color: var(--ink);
    background: transparent;
    padding: 11px 0;
}
.rv-search input::placeholder { color: var(--ink-muted); }

/* ─── Table card ────────────────────────────────────── */
.rv-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: var(--shadow-card);
    margin-bottom: 16px;
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

/* ─── Table ─────────────────────────────────────────── */
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
    white-space: nowrap;
}

.th-sortable {
    cursor: pointer;
    user-select: none;
    transition: color .15s;
}
.th-sortable:hover { color: var(--green-600); }
.sort-icon {
    display: inline-block;
    margin-left: 4px;
    opacity: 0.5;
    font-size: 9px;
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

/* ─── Action buttons ─────────────────────────────────── */
.td-actions { display: flex; gap: 6px; align-items: center; }

.btn-icon {
    width: 32px; height: 32px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    background: var(--white);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: all .17s ease;
    flex-shrink: 0;
}
.btn-icon img { width: 15px; height: 15px; object-fit: contain; }

.btn-icon-delete:hover {
    background: #fceaea;
    border-color: #f5c2c2;
    transform: scale(1.08);
}
.btn-icon-edit:hover {
    background: var(--green-100);
    border-color: var(--green-400);
    transform: scale(1.08);
}

/* ─── Bottom buttons ─────────────────────────────────── */
.rv-btn-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}

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
    font-family: 'Sora', sans-serif;
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
    color: var(--ink-mid);
    border: 1.5px solid var(--border);
}
.btn-ghost:hover {
    background: var(--green-100);
    border-color: var(--green-500);
    color: var(--green-700);
}

/* ─── Empty state ────────────────────────────────────── */
.rv-empty {
    padding: 40px 20px;
    text-align: center;
    color: var(--ink-muted);
    font-size: 13px;
}

/* ─── Modal overlay ──────────────────────────────────── */
.rv-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(13,31,5,0.45);
    backdrop-filter: blur(3px);
    -webkit-backdrop-filter: blur(3px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    pointer-events: none;
    transition: opacity .22s ease;
}
.rv-modal-overlay.active {
    opacity: 1;
    pointer-events: all;
}

.rv-modal {
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: 0 24px 60px rgba(13,31,5,0.25), 0 4px 16px rgba(0,0,0,0.08);
    width: 360px;
    max-width: 90vw;
    overflow: hidden;
    transform: translateY(16px) scale(0.97);
    transition: transform .25s cubic-bezier(.34,1.4,.64,1);
}
.rv-modal-overlay.active .rv-modal {
    transform: translateY(0) scale(1);
}

.rv-modal-header {
    padding: 22px 24px 0;
    display: flex;
    align-items: center;
    gap: 13px;
}

.rv-modal-ico {
    width: 42px; height: 42px;
    border-radius: 10px;
    background: #fceaea;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.rv-modal-ico img { width: 20px; height: 20px; object-fit: contain; }

.rv-modal-title {
    font-family: 'Instrument Serif', serif;
    font-size: 20px;
    color: var(--ink);
    font-weight: 400;
    letter-spacing: -0.3px;
}

.rv-modal-body {
    padding: 14px 24px 22px;
}
.rv-modal-body p {
    font-size: 13px;
    color: var(--ink-mid);
    line-height: 1.6;
}
.rv-modal-body strong {
    color: var(--ink);
    font-weight: 600;
}

.rv-modal-divider {
    height: 1px;
    background: var(--border);
    margin: 0 24px;
}

.rv-modal-actions {
    padding: 16px 24px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    background: var(--surface);
}

.btn-modal-cancel {
    background: transparent;
    color: var(--ink-mid);
    border: 1.5px solid var(--border);
    padding: 8px 18px;
    border-radius: var(--radius-sm);
    font-family: 'Sora', sans-serif;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all .17s;
}
.btn-modal-cancel:hover {
    background: var(--green-100);
    border-color: var(--green-400);
    color: var(--green-700);
}

.btn-modal-confirm {
    background: #c0392b;
    color: #fff;
    border: none;
    padding: 8px 20px;
    border-radius: var(--radius-sm);
    font-family: 'Sora', sans-serif;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all .17s;
    display: flex;
    align-items: center;
    gap: 7px;
}
.btn-modal-confirm img { width: 13px; height: 13px; object-fit: contain; filter: brightness(0) invert(1); }
.btn-modal-confirm:hover {
    background: #a93226;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(192,57,43,0.35);
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
        <h1>Envíos por <em>Región</em></h1>
        <p>Administra costos, zonas y estados de cada región de envío</p>
    </div>

    <div class="rv-stats">
        <div class="rv-stat">
            <div class="rv-stat-val">{{ count($envios) }}</div>
            <div class="rv-stat-lbl">Regiones totales</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val"><span class="dot dot-on"></span>{{ $activas }}</div>
            <div class="rv-stat-lbl">Activas</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val"><span class="dot dot-off"></span>{{ $inactivas }}</div>
            <div class="rv-stat-lbl">Inactivas</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">Bs. {{ number_format($promedio, 1) }}</div>
            <div class="rv-stat-lbl">Costo promedio</div>
        </div>
    </div>
</div>

{{-- ── TOOLBAR ─────────────────────────────────────── --}}
<div class="rv-toolbar">
    <div class="rv-search">
        <img src="/images/icono-buscar.png" alt="" class="rv-search-icon">
        <input type="text" id="buscador" placeholder="Buscar región, ciudad..." oninput="filtrarTabla()">
    </div>
</div>

{{-- ── TABLA ───────────────────────────────────────── --}}
<div class="rv-card">
    <div class="rv-card-head">
        <div class="rv-card-title">
            <div class="rv-card-icon">
                <img src="/images/icono-envio.png" alt="">
            </div>
            Tarifas de envío
        </div>
        <span class="rv-badge">{{ count($envios) }} registros</span>
    </div>

    <table>
        <thead>
            <tr>
                <th class="th-sortable" onclick="sortTable(0)">Región <span class="sort-icon">⇅</span></th>
                <th>Ciudad</th>
                <th class="th-sortable" onclick="sortTable(2)">Costo (Bs.) <span class="sort-icon">⇅</span></th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaBody">
            @foreach($envios as $i => $e)
            <tr data-index="{{ $i }}"
                data-region="{{ $e[0] }}"
                data-ciudad="{{ $e[1] }}"
                data-costo="{{ $e[2] }}"
                data-estado="{{ $e[3] }}">
                <td>{{ $e[0] }}</td>
                <td class="muted">{{ $e[1] }}</td>
                <td class="price">{{ $e[2] }}</td>
                <td>
                    <span class="pill {{ $e[3] === 'Activo' ? 'pill-on' : 'pill-off' }}">
                        {{ $e[3] }}
                    </span>
                </td>
                <td>
                    <div class="td-actions">
                        <a href="{{ route('admin.envios.editar') }}" class="btn-icon btn-icon-edit" title="Editar">
                            <img src="/images/icono-editar.png" alt="Editar">
                        </a>
                        <button class="btn-icon btn-icon-delete" title="Eliminar"
                            onclick="confirmarEliminar('{{ $e[0] }}', '{{ $e[1] }}', this)">
                            <img src="/images/icono-basurero.png" alt="Eliminar">
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div id="rv-empty" class="rv-empty" style="display:none;">
        Sin resultados para tu búsqueda.
    </div>
</div>

{{-- ── BOTONES ─────────────────────────────────────── --}}
<div class="rv-btn-row">
    <a href="{{ route('admin.envios.index') }}" class="btn btn-ghost">
        <img src="/images/icono-regresar.png" alt="">
        Regresar
    </a>
    <a href="{{ route('admin.envios.crear') }}" class="btn btn-dark">
        <img src="/images/icono-agregar.png" alt="">
        Agregar Envío
    </a>
</div>

{{-- ── MODAL CONFIRMAR ELIMINAR ────────────────────── --}}
<div class="rv-modal-overlay" id="modalEliminar">
    <div class="rv-modal">
        <div class="rv-modal-header">
            <div class="rv-modal-ico">
                <img src="/images/icono-basurero.png" alt="">
            </div>
            <div class="rv-modal-title">Eliminar envío</div>
        </div>
        <div class="rv-modal-body">
            <p>¿Estás seguro que deseas eliminar la región <strong id="modalRegion"></strong> de <strong id="modalCiudad"></strong>?</p>
            <p style="margin-top:8px; color: var(--ink-muted);">Esta acción no se puede deshacer.</p>
        </div>
        <div class="rv-modal-divider"></div>
        <div class="rv-modal-actions">
            <button class="btn-modal-cancel" onclick="cerrarModal()">No, cancelar</button>
            <button class="btn-modal-confirm" onclick="ejecutarEliminar()">
                <img src="/images/icono-basurero.png" alt="">
                Sí, eliminar
            </button>
        </div>
    </div>
</div>

{{-- ── TOAST ────────────────────────────────────────── --}}
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
    <img src="/images/icono-basurero.png" alt="" style="width:15px;height:15px;object-fit:contain;filter:brightness(0) invert(1);">
    <span id="rv-toast-msg">Envío eliminado.</span>
</div>

@push('scripts')
<script>
// ── Búsqueda ──────────────────────────────────────────
function filtrarTabla() {
    const texto = document.getElementById('buscador').value.toLowerCase();
    const filas = document.querySelectorAll('#tablaBody tr');
    let visibles = 0;
    filas.forEach(fila => {
        const coincide = fila.innerText.toLowerCase().includes(texto);
        fila.style.display = coincide ? '' : 'none';
        if (coincide) visibles++;
    });
    document.getElementById('rv-empty').style.display = visibles === 0 ? 'block' : 'none';
}

// ── Ordenar columnas ──────────────────────────────────
let sortDir = {};
function sortTable(col) {
    sortDir[col] = !sortDir[col];
    const tbody = document.getElementById('tablaBody');
    const filas = Array.from(tbody.querySelectorAll('tr'));
    filas.sort((a, b) => {
        const va = a.cells[col].innerText.trim();
        const vb = b.cells[col].innerText.trim();
        const na = parseFloat(va), nb = parseFloat(vb);
        const cmp = (!isNaN(na) && !isNaN(nb)) ? na - nb : va.localeCompare(vb, 'es');
        return sortDir[col] ? cmp : -cmp;
    });
    filas.forEach(f => tbody.appendChild(f));
}

// ── Modal eliminar ────────────────────────────────────
let pendienteEliminar = null;

function confirmarEliminar(region, ciudad, btn) {
    const fila = btn.closest('tr');
    pendienteEliminar = { region, ciudad, fila };
    document.getElementById('modalRegion').textContent = region;
    document.getElementById('modalCiudad').textContent = ciudad;
    document.getElementById('modalEliminar').classList.add('active');
}

function cerrarModal() {
    document.getElementById('modalEliminar').classList.remove('active');
    pendienteEliminar = null;
}

function ejecutarEliminar() {
    if (!pendienteEliminar) return;
    cerrarModal();

    // Simular eliminación: animar y quitar la fila del DOM
    const fila = pendienteEliminar.fila;
    fila.style.transition = 'opacity .35s ease, transform .35s ease';
    fila.style.opacity    = '0';
    fila.style.transform  = 'translateX(20px)';

    setTimeout(() => {
        fila.remove();
        // Recalcular contador del badge
        const total = document.querySelectorAll('#tablaBody tr').length;
        const badge = document.querySelector('.rv-badge');
        if (badge) badge.textContent = total + ' registros';

        // Mostrar mensaje vacío si no quedan filas
        const empty = document.getElementById('rv-empty');
        if (total === 0 && empty) empty.style.display = 'block';
    }, 370);

    // Mostrar toast
    mostrarToast('Envío eliminado correctamente.');
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

// Cerrar al hacer clic en el overlay
document.getElementById('modalEliminar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

// Cerrar con Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') cerrarModal();
});
</script>
@endpush

@endsection