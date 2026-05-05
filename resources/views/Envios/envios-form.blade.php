@extends('layout')
@section('title', isset($envio) ? 'Editar Envío — Rayo Verde' : 'Agregar Envío — Rayo Verde')

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
    --error:     #c0392b;
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
    border-radius: var(--radius-lg);
    padding: 32px 36px 36px;
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
.rv-hero-top { display: flex; align-items: center; gap: 14px; margin-bottom: 20px; position: relative; }
.rv-logo {
    width: 48px; height: 48px; border-radius: 12px;
    background: rgba(255,255,255,0.12); border: 1.5px solid rgba(255,255,255,0.25);
    display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;
}
.rv-logo img { width: 100%; height: 100%; object-fit: contain; }
.rv-brand-name { font-family: 'Instrument Serif', serif; font-size: 20px; color: #fff; letter-spacing: -0.3px; }
.rv-brand-sub { font-size: 10px; color: rgba(255,255,255,0.45); letter-spacing: 2px; text-transform: uppercase; margin-top: 2px; }
.rv-hero-body { position: relative; }
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
.rv-hero p { color: rgba(255,255,255,0.55); font-size: 13px; font-weight: 300; }

@media (max-width: 600px) {
    .rv-hero { padding: 24px 20px 28px; border-radius: var(--radius-md); }
    .rv-hero h1 { font-size: 28px; }
}

/* ─── Form card ─────────────────────────────────────── */
/*
   NO usar overflow:hidden aquí — cortaría los dropdowns.
   Cada sección interna maneja su propio border-radius.
*/
.rv-form-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-card);
    margin-bottom: 16px;
}

.rv-form-head {
    padding: 16px 24px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 10px;
    font-size: 14px; font-weight: 600; color: var(--ink); letter-spacing: -0.2px;
    background: var(--white);
    border-radius: var(--radius-md) var(--radius-md) 0 0;
}
.rv-card-icon {
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    background: var(--green-100); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.rv-card-icon img { width: 18px; height: 18px; object-fit: contain; }

.rv-form-body { padding: 28px 24px; }

/* ─── Responsive grid ────────────────────────────────── */
.rv-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px 32px;    /* más separación vertical para que el dropdown tenga aire */
    align-items: start; /* cada celda crece independiente — no estira filas */
}

@media (max-width: 700px) {
    .rv-form-grid  { grid-template-columns: 1fr; gap: 28px; }
    .rv-form-body  { padding: 20px 16px; }
    .rv-form-head  { padding: 14px 16px; }
    .rv-form-foot  { padding: 14px 16px; flex-wrap: wrap; gap: 10px; }
    .rv-form-foot .btn { flex: 1; justify-content: center; }
}

/* ─── Field — stacking context para z-index del dropdown ─ */
.rv-field {
    position: relative;
    /* Sin min-height forzado: el dropdown flota fuera del flujo */
}
/* El campo con foco sube sobre sus hermanos del grid */
.rv-field:focus-within { z-index: 50; }

.rv-label {
    display: block; font-size: 11px; font-weight: 600; color: var(--ink-muted);
    letter-spacing: 1px; text-transform: uppercase; margin-bottom: 7px;
}
.rv-label span { color: var(--error); margin-left: 2px; }

/* ─── Autocomplete ───────────────────────────────────── */
.rv-autocomplete { position: relative; }

.rv-input {
    width: 100%; padding: 11px 40px 11px 14px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-family: 'Sora', sans-serif; font-size: 13px; color: var(--ink);
    background: var(--white); outline: none;
    transition: border-color .2s, box-shadow .2s; appearance: none;
}
.rv-input:focus { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(79,144,32,0.1); }
.rv-input.is-error { border-color: var(--error); box-shadow: 0 0 0 3px rgba(192,57,43,0.08); }
.rv-input::placeholder { color: var(--ink-muted); }

.rv-input-icon {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    width: 16px; height: 16px; object-fit: contain; opacity: 0.35; pointer-events: none;
}

/* ─── Dropdown — siempre por encima de todo ──────────── */
.rv-dropdown {
    position: absolute;
    top: calc(100% + 6px); left: 0; right: 0;
    background: var(--white);
    border: 1.5px solid var(--green-400); border-radius: var(--radius-sm);
    box-shadow: 0 10px 32px rgba(26,61,6,0.18);
    z-index: 9999;
    max-height: 200px; overflow-y: auto;
    display: none;
}
.rv-dropdown.open { display: block; }

.rv-dropdown-item {
    padding: 10px 14px; font-size: 13px; color: var(--ink);
    cursor: pointer; transition: background .13s; border-bottom: 1px solid #f0f4eb;
}
.rv-dropdown-item:last-child { border-bottom: none; }
.rv-dropdown-item:hover { background: var(--green-50); color: var(--green-700); }
.rv-dropdown-item mark { background: none; color: var(--green-600); font-weight: 700; }
.rv-dropdown-empty { padding: 14px; font-size: 12px; color: var(--ink-muted); text-align: center; }

/* ─── Price input ────────────────────────────────────── */
.rv-price-wrap {
    display: flex; align-items: stretch;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    overflow: hidden; transition: border-color .2s, box-shadow .2s; background: var(--white);
}
.rv-price-wrap:focus-within { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(79,144,32,0.1); }
.rv-price-wrap.is-error { border-color: var(--error); box-shadow: 0 0 0 3px rgba(192,57,43,0.08); }
.rv-price-prefix, .rv-price-suffix {
    padding: 11px 12px; font-size: 12px; font-weight: 600; color: var(--ink-muted);
    background: var(--surface); white-space: nowrap; flex-shrink: 0;
    display: flex; align-items: center;
}
.rv-price-prefix { border-right: 1.5px solid var(--border); }
.rv-price-suffix { border-left: 1.5px solid var(--border); }
.rv-price-input {
    flex: 1; min-width: 0; border: none; outline: none; padding: 11px 12px;
    font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 600;
    color: var(--green-700); font-variant-numeric: tabular-nums; background: transparent;
}

/* ─── Estado pills ───────────────────────────────────── */
.rv-toggle-group { display: flex; gap: 10px; flex-wrap: wrap; }
.rv-toggle-option { display: none; }
.rv-toggle-label {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 20px; border: 1.5px solid var(--border); border-radius: 30px;
    font-size: 12px; font-weight: 600; color: var(--ink-muted);
    cursor: pointer; transition: all .18s; user-select: none;
}
.rv-toggle-label::before {
    content: ''; width: 8px; height: 8px; border-radius: 50%; background: var(--border); transition: background .18s;
}
.rv-toggle-option:checked + .rv-toggle-label {
    border-color: var(--green-500); background: var(--green-50); color: var(--green-700);
}
.rv-toggle-option:checked + .rv-toggle-label::before { background: var(--green-500); }
.rv-toggle-option.off-option:checked + .rv-toggle-label {
    border-color: #d94a4a; background: #fceaea; color: #7a1f1f;
}
.rv-toggle-option.off-option:checked + .rv-toggle-label::before { background: #d94a4a; }

/* ─── Error ──────────────────────────────────────────── */
.rv-error-msg { font-size: 11px; color: var(--error); margin-top: 5px; display: none; }
.rv-error-msg.visible { display: block; }

/* ─── Footer ─────────────────────────────────────────── */
.rv-form-foot {
    padding: 16px 24px; border-top: 1px solid var(--border);
    display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;
    background: var(--surface); border-radius: 0 0 var(--radius-md) var(--radius-md);
}

@media (max-width: 400px) {
    .rv-form-foot { flex-direction: column; }
    .rv-form-foot .btn { width: 100%; justify-content: center; }
}

/* ─── Buttons ────────────────────────────────────────── */
.btn {
    display: inline-flex; align-items: center; gap: 7px; padding: 10px 20px;
    border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
    border: none; cursor: pointer; text-decoration: none;
    transition: all .18s ease; letter-spacing: 0.2px; font-family: 'Sora', sans-serif;
}
.btn img { width: 14px; height: 14px; object-fit: contain; }
.btn-dark { background: var(--green-600); color: #fff; }
.btn-dark:hover { background: var(--green-700); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(59,109,17,0.3); }
.btn-ghost { background: transparent; color: var(--ink-mid); border: 1.5px solid var(--border); }
.btn-ghost:hover { background: var(--green-100); border-color: var(--green-500); color: var(--green-700); }

/* ─── Toast ──────────────────────────────────────────── */
.rv-toast {
    position: fixed; bottom: 28px; right: 28px;
    background: var(--green-700); color: #fff; padding: 13px 20px;
    border-radius: var(--radius-sm); font-size: 13px; font-weight: 500;
    display: flex; align-items: center; gap: 10px;
    box-shadow: 0 8px 24px rgba(26,61,6,0.3);
    transform: translateY(20px); opacity: 0; transition: all .3s ease;
    z-index: 9999; pointer-events: none;
}
.rv-toast.show { opacity: 1; transform: translateY(0); }
.rv-toast img { width: 16px; height: 16px; object-fit: contain; filter: brightness(0) invert(1); }
</style>
@endpush

@section('content')

@php
$ciudades = ['La Paz','Oruro','Potosí','Cochabamba','Chuquisaca','Tarija','Santa Cruz','Beni','Pando','El Alto'];
$regiones = ['Zona Sur','Centro','El Alto','Cercado','Distrito 1','Provincia Aroma'];

$editando  = isset($envio);
$valCiudad = $editando ? $envio['ciudad'] : '';
$valRegion = $editando ? $envio['region'] : '';
$valCosto  = $editando ? $envio['costo']  : '';
$valEstado = $editando ? $envio['estado'] : 'Activo';
@endphp

{{-- ── HERO ───────────────────────────────────────── --}}
<div class="rv-hero">
    <div class="rv-hero-top">
        <div class="rv-logo"><img src="/images/logo.png" alt="Rayo Verde"></div>
        <div>
            <div class="rv-brand-name">Rayo Verde</div>
            <div class="rv-brand-sub">Panel Administrativo</div>
        </div>
    </div>
    <div class="rv-hero-body">
        <div class="rv-hero-eyebrow">Logística</div>
        <h1>{{ $editando ? 'Editar' : 'Agregar' }} <em>Envío</em></h1>
        <p>{{ $editando ? 'Modifica los datos del envío seleccionado' : 'Completa el formulario para registrar una nueva tarifa de envío' }}</p>
    </div>
</div>

{{-- ── FORMULARIO ──────────────────────────────────── --}}
<div class="rv-form-card">
    <div class="rv-form-head">
        <div class="rv-card-icon"><img src="/images/icono-envio.png" alt=""></div>
        {{ $editando ? 'Editar tarifa de envío' : 'Nueva tarifa de envío' }}
    </div>

    <div class="rv-form-body">
        <div class="rv-form-grid">

            {{-- Ciudad --}}
            <div class="rv-field">
                <label class="rv-label">Seleccionar Ciudad <span>*</span></label>
                <div class="rv-autocomplete">
                    <input type="text" class="rv-input" id="input-ciudad"
                        placeholder="Buscar ciudad..." value="{{ $valCiudad }}"
                        autocomplete="off"
                        oninput="acFilter('ciudad')" onfocus="acOpen('ciudad')" onblur="acBlur('ciudad')">
                    <img src="/images/icono-ciudad.png" alt="" class="rv-input-icon">
                    <div class="rv-dropdown" id="drop-ciudad"></div>
                    <input type="hidden" name="ciudad" id="hidden-ciudad" value="{{ $valCiudad }}">
                </div>
                <div class="rv-error-msg" id="err-ciudad">Selecciona una ciudad válida.</div>
            </div>

            {{-- Región --}}
            <div class="rv-field">
                <label class="rv-label">Seleccionar Región <span>*</span></label>
                <div class="rv-autocomplete">
                    <input type="text" class="rv-input" id="input-region"
                        placeholder="Buscar región..." value="{{ $valRegion }}"
                        autocomplete="off"
                        oninput="acFilter('region')" onfocus="acOpen('region')" onblur="acBlur('region')">
                    <img src="/images/icono-region.png" alt="" class="rv-input-icon">
                    <div class="rv-dropdown" id="drop-region"></div>
                    <input type="hidden" name="region" id="hidden-region" value="{{ $valRegion }}">
                </div>
                <div class="rv-error-msg" id="err-region">Selecciona una región válida.</div>
            </div>

            {{-- Costo --}}
            <div class="rv-field">
                <label class="rv-label">Costo de Envío <span>*</span></label>
                <div class="rv-price-wrap" id="wrap-costo">
                    <span class="rv-price-prefix">Bs.</span>
                    <input type="number" class="rv-price-input" id="input-costo"
                        name="costo" placeholder="0.00" step="0.01" min="0"
                        value="{{ $valCosto }}" oninput="validateCosto()">
                    <span class="rv-price-suffix">bolivianos</span>
                </div>
                <div class="rv-error-msg" id="err-costo">Ingresa un monto válido mayor a 0.</div>
            </div>

            {{-- Estado --}}
            <div class="rv-field">
                <label class="rv-label">Estado <span>*</span></label>
                <div class="rv-toggle-group">
                    <input type="radio" name="estado" id="est-activo" value="Activo"
                        class="rv-toggle-option" {{ $valEstado === 'Activo' ? 'checked' : '' }}>
                    <label for="est-activo" class="rv-toggle-label">Activo</label>

                    <input type="radio" name="estado" id="est-inactivo" value="Inactivo"
                        class="rv-toggle-option off-option" {{ $valEstado === 'Inactivo' ? 'checked' : '' }}>
                    <label for="est-inactivo" class="rv-toggle-label">Inactivo</label>
                </div>
                <div class="rv-error-msg" id="err-estado">Selecciona un estado.</div>
            </div>

        </div>
    </div>

    <div class="rv-form-foot">
        <a href="{{ route('admin.envios.gestionar') }}" class="btn btn-ghost">
            <img src="/images/icono-regresar.png" alt="">
            Regresar
        </a>
        <button type="button" class="btn btn-dark" onclick="guardarEnvio()">
            <img src="/images/icono-agregar.png" alt="">
            {{ $editando ? 'Guardar cambios' : 'Guardar Envío' }}
        </button>
    </div>
</div>

<div class="rv-toast" id="toast">
    <img src="/images/icono-envio.png" alt="">
    <span id="toast-msg"></span>
</div>

@push('scripts')
<script>
const AC_DATA = {
    ciudad: @json($ciudades),
    region: @json($regiones),
};
const AC_SELECTED = {
    ciudad: @json($valCiudad),
    region: @json($valRegion),
};

function renderDropdown(key, query) {
    const drop = document.getElementById('drop-' + key);
    const q = query.trim().toLowerCase();
    const filtered = q ? AC_DATA[key].filter(i => i.toLowerCase().includes(q)) : AC_DATA[key];
    if (!filtered.length) {
        drop.innerHTML = '<div class="rv-dropdown-empty">Sin resultados</div>';
        return;
    }
    drop.innerHTML = filtered.map(item => {
        const label = q ? item.replace(new RegExp(`(${q})`, 'gi'), '<mark>$1</mark>') : item;
        const safe  = item.replace(/'/g, "\\'");
        return `<div class="rv-dropdown-item" onmousedown="selectItem('${key}','${safe}')">${label}</div>`;
    }).join('');
}

function acOpen(key) {
    renderDropdown(key, document.getElementById('input-' + key).value);
    document.getElementById('drop-' + key).classList.add('open');
}
function acFilter(key) {
    renderDropdown(key, document.getElementById('input-' + key).value);
    document.getElementById('drop-' + key).classList.add('open');
    document.getElementById('hidden-' + key).value = '';
    AC_SELECTED[key] = '';
}
function acBlur(key) {
    setTimeout(() => {
        document.getElementById('drop-' + key).classList.remove('open');
        const v = document.getElementById('input-' + key).value;
        if (AC_DATA[key].includes(v)) {
            document.getElementById('hidden-' + key).value = v;
            AC_SELECTED[key] = v;
        } else {
            document.getElementById('hidden-' + key).value = '';
            AC_SELECTED[key] = '';
        }
    }, 160);
}
function selectItem(key, value) {
    document.getElementById('input-'  + key).value = value;
    document.getElementById('hidden-' + key).value = value;
    AC_SELECTED[key] = value;
    document.getElementById('drop-'   + key).classList.remove('open');
    document.getElementById('input-'  + key).classList.remove('is-error');
    document.getElementById('err-'    + key).classList.remove('visible');
}

function validateCosto() {
    const val = parseFloat(document.getElementById('input-costo').value);
    const ok  = !isNaN(val) && val > 0;
    document.getElementById('wrap-costo').classList.toggle('is-error', !ok);
    document.getElementById('err-costo').classList.toggle('visible',   !ok);
    return ok;
}

function guardarEnvio() {
    let valid = true;
    ['ciudad', 'region'].forEach(key => {
        const inp    = document.getElementById('input-'  + key).value;
        const hidden = AC_DATA[key].includes(inp) ? inp
                     : document.getElementById('hidden-' + key).value;
        if (!hidden) {
            document.getElementById('input-' + key).classList.add('is-error');
            document.getElementById('err-'   + key).classList.add('visible');
            valid = false;
        } else {
            document.getElementById('input-'  + key).classList.remove('is-error');
            document.getElementById('err-'    + key).classList.remove('visible');
            document.getElementById('hidden-' + key).value = hidden;
        }
    });
    if (!validateCosto()) valid = false;
    if (!document.querySelector('input[name="estado"]:checked')) {
        document.getElementById('err-estado').classList.add('visible');
        valid = false;
    } else {
        document.getElementById('err-estado').classList.remove('visible');
    }
    if (!valid) return;

    const isEdit = {{ $editando ? 'true' : 'false' }};
    showToast(isEdit ? 'Envío actualizado correctamente.' : 'Envío guardado correctamente.');
}

function showToast(msg) {
    const t = document.getElementById('toast');
    document.getElementById('toast-msg').textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3200);
}
</script>
@endpush

@endsection