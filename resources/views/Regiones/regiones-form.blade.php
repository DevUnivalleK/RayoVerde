@extends('layout')
@section('title', isset($zona) ? 'Editar Zona — Rayo Verde' : 'Agregar Zona — Rayo Verde')

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
.rv-card-icon svg { width: 18px; height: 18px; }

.rv-form-body { padding: 36px 32px 40px; }

/* ─── Descripción del formulario ────────────────────── */
.rv-form-desc {
    display: flex; align-items: flex-start; gap: 14px;
    background: var(--green-50); border: 1px solid var(--green-100);
    border-radius: var(--radius-sm); padding: 16px 18px;
    margin-bottom: 32px;
}
.rv-form-desc-icon {
    width: 36px; height: 36px; border-radius: 50%;
    background: var(--green-100); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.rv-form-desc-icon svg { width: 16px; height: 16px; color: var(--green-600); }
.rv-form-desc-text strong { display: block; font-size: 12px; font-weight: 600; color: var(--ink-mid); margin-bottom: 3px; }
.rv-form-desc-text span { font-size: 12px; color: var(--ink-muted); font-weight: 300; }

/* ─── Layout de dos campos centrados ────────────────── */
.rv-fields-wrap {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 28px;
    align-items: start;
    max-width: 680px;
    margin: 0 auto;
}

@media (max-width: 700px) {
    .rv-fields-wrap { grid-template-columns: 1fr; gap: 24px; max-width: 100%; }
    .rv-form-body  { padding: 24px 16px 28px; }
    .rv-form-head  { padding: 14px 16px; }
    .rv-form-foot  { padding: 14px 16px; flex-wrap: wrap; gap: 10px; }
    .rv-form-foot .btn { flex: 1; justify-content: center; }
}

/* ─── Field ─────────────────────────────────────────── */
.rv-field { position: relative; }
.rv-field:focus-within { z-index: 50; }

.rv-label {
    display: flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 600; color: var(--ink-muted);
    letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;
}
.rv-label span { color: var(--error); }
.rv-label-hint {
    font-size: 10px; font-weight: 400; text-transform: none; letter-spacing: 0;
    color: var(--ink-muted); margin-left: auto;
}

/* ─── Autocomplete (Ciudad) ──────────────────────────── */
.rv-autocomplete { position: relative; }

.rv-input {
    width: 100%; padding: 12px 42px 12px 14px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-family: 'Sora', sans-serif; font-size: 13px; color: var(--ink);
    background: var(--white); outline: none;
    transition: border-color .2s, box-shadow .2s; appearance: none;
}
.rv-input:focus { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(79,144,32,0.1); }
.rv-input.is-error { border-color: var(--error); box-shadow: 0 0 0 3px rgba(192,57,43,0.08); }
.rv-input::placeholder { color: var(--ink-muted); opacity: 0.7; }

.rv-input-icon-wrap {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;
    pointer-events: none;
}
.rv-input-icon-wrap svg { width: 15px; height: 15px; color: var(--ink-muted); opacity: 0.5; }

/* ─── Dropdown ───────────────────────────────────────── */
.rv-dropdown {
    position: absolute;
    top: calc(100% + 6px); left: 0; right: 0;
    background: var(--white);
    border: 1.5px solid var(--green-400); border-radius: var(--radius-sm);
    box-shadow: 0 10px 32px rgba(26,61,6,0.18);
    z-index: 9999;
    max-height: 220px; overflow-y: auto;
    display: none;
}
.rv-dropdown.open { display: block; }
.rv-dropdown-item {
    padding: 10px 14px; font-size: 13px; color: var(--ink);
    cursor: pointer; transition: background .13s; border-bottom: 1px solid #f0f4eb;
    display: flex; align-items: center; gap: 8px;
}
.rv-dropdown-item::before {
    content: ''; width: 6px; height: 6px; border-radius: 50%;
    background: var(--border); flex-shrink: 0; transition: background .13s;
}
.rv-dropdown-item:last-child { border-bottom: none; }
.rv-dropdown-item:hover { background: var(--green-50); color: var(--green-700); }
.rv-dropdown-item:hover::before { background: var(--green-500); }
.rv-dropdown-item mark { background: none; color: var(--green-600); font-weight: 700; }
.rv-dropdown-empty { padding: 16px; font-size: 12px; color: var(--ink-muted); text-align: center; }

/* ─── Input Nombre Región ────────────────────────────── */
.rv-input-region {
    width: 100%; padding: 12px 14px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-family: 'Sora', sans-serif; font-size: 13px; color: var(--ink);
    background: var(--white); outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.rv-input-region:focus { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(79,144,32,0.1); }
.rv-input-region.is-error { border-color: var(--error); box-shadow: 0 0 0 3px rgba(192,57,43,0.08); }
.rv-input-region::placeholder { color: var(--ink-muted); opacity: 0.7; }

/* ─── Contador de caracteres ─────────────────────────── */
.rv-char-count {
    font-size: 10px; color: var(--ink-muted); margin-top: 5px; text-align: right;
}
.rv-char-count.warn { color: #d97706; }

/* ─── Error ──────────────────────────────────────────── */
.rv-error-msg { font-size: 11px; color: var(--error); margin-top: 5px; display: none; }
.rv-error-msg.visible { display: flex; align-items: center; gap: 4px; }
.rv-error-msg::before { content: '⚠'; font-size: 10px; }

/* ─── Footer ─────────────────────────────────────────── */
.rv-form-foot {
    padding: 16px 24px; border-top: 1px solid var(--border);
    display: flex; justify-content: space-between; align-items: center; gap: 12px;
    background: var(--surface); border-radius: 0 0 var(--radius-md) var(--radius-md);
}
.rv-foot-note { font-size: 11px; color: var(--ink-muted); display: flex; align-items: center; gap: 5px; }
.rv-foot-note svg { width: 12px; height: 12px; }

@media (max-width: 400px) {
    .rv-form-foot { flex-direction: column; }
    .rv-form-foot .btn, .rv-form-foot .rv-foot-note { width: 100%; justify-content: center; }
}

/* ─── Buttons ────────────────────────────────────────── */
.btn {
    display: inline-flex; align-items: center; gap: 7px; padding: 10px 22px;
    border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
    border: none; cursor: pointer; text-decoration: none;
    transition: all .18s ease; letter-spacing: 0.2px; font-family: 'Sora', sans-serif;
}
.btn svg { width: 14px; height: 14px; }
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
.rv-toast svg { width: 16px; height: 16px; filter: brightness(0) invert(1); }
</style>
@endpush

@section('content')

@php
$ciudades = ['La Paz','Oruro','Potosí','Cochabamba','Chuquisaca','Tarija','Santa Cruz','Beni','Pando','El Alto'];

$editando   = isset($zona);
$valCiudad  = $editando ? $zona['ciudad'] : '';
$valNombre  = $editando ? $zona['nombre'] : '';
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
        <div class="rv-hero-eyebrow">Zonas de Cobertura</div>
        <h1>{{ $editando ? 'Editar' : 'Nueva' }} <em>Zona</em></h1>
        <p>{{ $editando ? 'Modifica los datos de la zona seleccionada' : 'Registra una nueva zona asociando una ciudad con su región correspondiente' }}</p>
    </div>
</div>

{{-- ── FORMULARIO ──────────────────────────────────── --}}
<div class="rv-form-card">
    <div class="rv-form-head">
        <div class="rv-card-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--green-600)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                <circle cx="12" cy="9" r="2.5"/>
            </svg>
        </div>
        {{ $editando ? 'Editar zona de cobertura' : 'Nueva zona de cobertura' }}
    </div>

    <div class="rv-form-body">

        {{-- Descripción informativa --}}
        <div class="rv-form-desc">
            <div class="rv-form-desc-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <div class="rv-form-desc-text">
                <strong>¿Cómo funciona?</strong>
                <span>Selecciona la ciudad del departamento y escribe el nombre de la región o zona específica dentro de esa ciudad. Cada zona queda asociada a una ciudad para organizar la cobertura de envíos.</span>
            </div>
        </div>

        <div class="rv-fields-wrap">

            {{-- Ciudad --}}
            <div class="rv-field">
                <label class="rv-label" for="input-ciudad">
                    Seleccionar Ciudad <span>*</span>
                </label>
                <div class="rv-autocomplete">
                    <input type="text" class="rv-input" id="input-ciudad"
                        placeholder="Buscar ciudad..." value="{{ $valCiudad }}"
                        autocomplete="off"
                        oninput="acFilter('ciudad')" onfocus="acOpen('ciudad')" onblur="acBlur('ciudad')">
                    <div class="rv-input-icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </div>
                    <div class="rv-dropdown" id="drop-ciudad"></div>
                    <input type="hidden" name="ciudad" id="hidden-ciudad" value="{{ $valCiudad }}">
                </div>
                <div class="rv-error-msg" id="err-ciudad">Selecciona una ciudad válida.</div>
            </div>

            {{-- Nombre Región --}}
            <div class="rv-field">
                <label class="rv-label" for="input-nombre">
                    Nombre Región <span>*</span>
                    <span class="rv-label-hint">máx. 60 caracteres</span>
                </label>
                <input type="text" class="rv-input-region" id="input-nombre"
                    name="nombre" placeholder="Ej: Zona Sur, Miraflores..."
                    maxlength="60" value="{{ $valNombre }}"
                    oninput="onNombreInput()" onblur="validateNombre()">
                <div class="rv-char-count" id="char-count">0 / 60</div>
                <div class="rv-error-msg" id="err-nombre">Ingresa el nombre de la región.</div>
            </div>

        </div>
    </div>

    <div class="rv-form-foot">
        <span class="rv-foot-note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            Los campos con <strong>&nbsp;*&nbsp;</strong> son obligatorios
        </span>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('admin.regiones.gestionar') }}" class="btn btn-ghost">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                Regresar
            </a>
            <button type="button" class="btn btn-dark" onclick="guardarZona()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                    <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                </svg>
                {{ $editando ? 'Guardar cambios' : 'Guardar Zona' }}
            </button>
        </div>
    </div>
</div>

<div class="rv-toast" id="toast">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
        <circle cx="12" cy="9" r="2.5"/>
    </svg>
    <span id="toast-msg"></span>
</div>

@push('scripts')
<script>
const AC_DATA = {
    ciudad: @json($ciudades),
};
const AC_SELECTED = {
    ciudad: @json($valCiudad),
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

function onNombreInput() {
    const inp = document.getElementById('input-nombre');
    const len = inp.value.length;
    const counter = document.getElementById('char-count');
    counter.textContent = len + ' / 60';
    counter.classList.toggle('warn', len >= 50);
    if (len > 0) {
        inp.classList.remove('is-error');
        document.getElementById('err-nombre').classList.remove('visible');
    }
}

function validateNombre() {
    const val = document.getElementById('input-nombre').value.trim();
    const ok  = val.length > 0;
    document.getElementById('input-nombre').classList.toggle('is-error', !ok);
    document.getElementById('err-nombre').classList.toggle('visible',    !ok);
    return ok;
}

function guardarZona() {
    let valid = true;

    // Validar ciudad
    const ciudadInput  = document.getElementById('input-ciudad').value;
    const ciudadHidden = AC_DATA.ciudad.includes(ciudadInput)
        ? ciudadInput
        : document.getElementById('hidden-ciudad').value;
    if (!ciudadHidden) {
        document.getElementById('input-ciudad').classList.add('is-error');
        document.getElementById('err-ciudad').classList.add('visible');
        valid = false;
    } else {
        document.getElementById('input-ciudad').classList.remove('is-error');
        document.getElementById('err-ciudad').classList.remove('visible');
        document.getElementById('hidden-ciudad').value = ciudadHidden;
    }

    // Validar nombre región
    if (!validateNombre()) valid = false;

    if (!valid) return;

    const isEdit = {{ $editando ? 'true' : 'false' }};
    showToast(isEdit ? 'Zona actualizada correctamente.' : 'Zona registrada correctamente.');
}

function showToast(msg) {
    const t = document.getElementById('toast');
    document.getElementById('toast-msg').textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3200);
}

// Inicializar contador
onNombreInput();
</script>
@endpush

@endsection