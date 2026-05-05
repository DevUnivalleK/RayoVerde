@extends('layout')
@section('title', isset($faq) ? 'Editar FAQ — Rayo Verde' : 'Nueva FAQ — Rayo Verde')

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

/* ─── Hero ──────────────────────────────────────────────────────── */
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
.rv-brand-sub  { font-size: 10px; color: rgba(255,255,255,0.45); letter-spacing: 2px; text-transform: uppercase; margin-top: 2px; }
.rv-hero-body  { position: relative; }
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

/* ─── Form card ─────────────────────────────────────────────────── */
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
.rv-card-icon svg { width: 17px; height: 17px; }

.rv-form-body { padding: 28px 24px 32px; }

/* ─── Grid layout ────────────────────────────────────────────────── */
.rv-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px 28px;
    align-items: start;
}
/* Categoría: media columna | Las textareas: columna completa */
.rv-field-full { grid-column: 1 / -1; }

@media (max-width: 700px) {
    .rv-form-grid  { grid-template-columns: 1fr; gap: 22px; }
    .rv-field-full { grid-column: 1; }
    .rv-form-body  { padding: 20px 16px 24px; }
    .rv-form-head  { padding: 14px 16px; }
    .rv-form-foot  { padding: 14px 16px; flex-wrap: wrap; gap: 10px; }
    .rv-form-foot .btn { flex: 1; justify-content: center; }
}

/* ─── Field ──────────────────────────────────────────────────────── */
.rv-field { position: relative; }
.rv-field:focus-within { z-index: 50; }

.rv-label {
    display: flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 600; color: var(--ink-muted);
    letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;
}
.rv-label .req { color: var(--error); }
.rv-label .hint {
    font-size: 10px; font-weight: 400; text-transform: none;
    letter-spacing: 0; color: var(--ink-muted); margin-left: auto;
}

/* ─── Autocomplete (Categoría) ───────────────────────────────────── */
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
.rv-input::placeholder { color: var(--ink-muted); opacity: 0.7; }

.rv-chevron {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    pointer-events: none; color: var(--ink-muted); opacity: 0.45;
}
.rv-chevron svg { width: 15px; height: 15px; display: block; }

/* ─── Dropdown ───────────────────────────────────────────────────── */
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
    cursor: pointer; transition: background .13s;
    border-bottom: 1px solid #f0f4eb;
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
.rv-dropdown-empty { padding: 14px; font-size: 12px; color: var(--ink-muted); text-align: center; }

/* ─── Textarea ───────────────────────────────────────────────────── */
.rv-textarea {
    width: 100%;
    padding: 13px 14px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-family: 'Sora', sans-serif; font-size: 13px; color: var(--ink);
    background: var(--white); outline: none; resize: vertical;
    transition: border-color .2s, box-shadow .2s; line-height: 1.6;
    min-height: 120px;
}
.rv-textarea:focus { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(79,144,32,0.1); }
.rv-textarea.is-error { border-color: var(--error); box-shadow: 0 0 0 3px rgba(192,57,43,0.08); }
.rv-textarea::placeholder { color: var(--ink-muted); opacity: 0.7; }

/* Textarea de respuesta más alta — tiene más contenido esperado */
.rv-textarea-lg { min-height: 160px; }

/* ─── Contador de caracteres ─────────────────────────────────────── */
.rv-textarea-foot {
    display: flex; justify-content: space-between; align-items: center;
    margin-top: 5px;
}
.rv-char-count { font-size: 10px; color: var(--ink-muted); }
.rv-char-count.warn { color: #d97706; }

/* ─── Badge de categoría seleccionada ────────────────────────────── */
.rv-badge-selected {
    display: none; align-items: center; gap: 6px;
    background: var(--green-50); border: 1px solid var(--green-100);
    border-radius: 20px; padding: 4px 10px 4px 8px;
    font-size: 11px; font-weight: 600; color: var(--green-700);
    margin-top: 7px;
}
.rv-badge-selected.show { display: inline-flex; }
.rv-badge-selected svg { width: 12px; height: 12px; }

/* ─── Error ──────────────────────────────────────────────────────── */
.rv-error-msg { font-size: 11px; color: var(--error); margin-top: 5px; display: none; }
.rv-error-msg.visible { display: flex; align-items: center; gap: 4px; }
.rv-error-msg::before { content: '⚠'; font-size: 10px; }

/* ─── Separador de secciones ─────────────────────────────────────── */
.rv-section-divider {
    grid-column: 1 / -1;
    display: flex; align-items: center; gap: 12px;
    margin: 4px 0;
}
.rv-section-divider span {
    font-size: 10px; font-weight: 600; letter-spacing: 1.5px;
    text-transform: uppercase; color: var(--ink-muted); white-space: nowrap;
}
.rv-section-divider::before, .rv-section-divider::after {
    content: ''; flex: 1; height: 1px; background: var(--border);
}

/* ─── Footer ─────────────────────────────────────────────────────── */
.rv-form-foot {
    padding: 16px 24px; border-top: 1px solid var(--border);
    display: flex; justify-content: space-between; align-items: center; gap: 12px;
    background: var(--surface); border-radius: 0 0 var(--radius-md) var(--radius-md);
}
.rv-foot-note { font-size: 11px; color: var(--ink-muted); display: flex; align-items: center; gap: 5px; }
.rv-foot-note svg { width: 12px; height: 12px; }
.rv-foot-actions { display: flex; gap: 10px; }

@media (max-width: 560px) {
    .rv-form-foot { flex-direction: column; align-items: stretch; }
    .rv-foot-actions { flex-direction: column; }
    .rv-foot-actions .btn { justify-content: center; }
}

/* ─── Buttons ────────────────────────────────────────────────────── */
.btn {
    display: inline-flex; align-items: center; gap: 7px; padding: 10px 20px;
    border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
    border: none; cursor: pointer; text-decoration: none;
    transition: all .18s ease; letter-spacing: 0.2px; font-family: 'Sora', sans-serif;
}
.btn svg { width: 14px; height: 14px; flex-shrink: 0; }
.btn-dark  { background: var(--green-600); color: #fff; }
.btn-dark:hover  { background: var(--green-700); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(59,109,17,0.3); }
.btn-ghost { background: transparent; color: var(--ink-mid); border: 1.5px solid var(--border); }
.btn-ghost:hover { background: var(--green-100); border-color: var(--green-500); color: var(--green-700); }

/* ─── Toast ──────────────────────────────────────────────────────── */
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
.rv-toast svg { width: 16px; height: 16px; flex-shrink: 0; }
</style>
@endpush

@section('content')

@php
$categorias = [
    'Envíos y Entregas',
    'Pagos y Facturación',
    'Rastreo de Pedido',
    'Devoluciones y Cambios',
    'Cuenta y Registro',
    'Productos y Stock',
    'Soporte Técnico',
    'Promociones y Descuentos',
];

$editando    = isset($faq);
$valCat      = $editando ? $faq['categoria']  : '';
$valPregunta = $editando ? $faq['pregunta']   : '';
$valRespuesta= $editando ? $faq['respuesta']  : '';
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
        <h1>{{ $editando ? 'Editar' : 'Nueva' }} <em>FAQ</em></h1>
        <p>{{ $editando ? 'Modifica los datos de la pregunta frecuente seleccionada' : 'Completa el formulario para registrar una nueva pregunta frecuente del chatbot' }}</p>
    </div>
</div>

{{-- ── FORMULARIO ───────────────────────────────────────── --}}
<div class="rv-form-card">

    <div class="rv-form-head">
        <div class="rv-card-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--green-600)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
        </div>
        {{ $editando ? 'Editar pregunta frecuente' : 'Nueva pregunta frecuente' }}
    </div>

    <div class="rv-form-body">
        <div class="rv-form-grid">

            {{-- ── Categoría ─────────────────────────────── --}}
            <div class="rv-field">
                <label class="rv-label" for="input-categoria">
                    Categoría <span class="req">*</span>
                </label>
                <div class="rv-autocomplete">
                    <input type="text" class="rv-input" id="input-categoria"
                        placeholder="Buscar categoría..."
                        value="{{ $valCat }}" autocomplete="off"
                        oninput="acFilter()" onfocus="acOpen()" onblur="acBlur()">
                    <div class="rv-chevron">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </div>
                    <div class="rv-dropdown" id="drop-categoria"></div>
                    <input type="hidden" name="categoria" id="hidden-categoria" value="{{ $valCat }}">
                </div>
                <div class="rv-badge-selected {{ $valCat ? 'show' : '' }}" id="badge-cat">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span id="badge-cat-text">{{ $valCat }}</span>
                </div>
                <div class="rv-error-msg" id="err-categoria">Selecciona una categoría válida.</div>
            </div>

            {{-- ── Columna vacía para mantener grid 2 col ── --}}
            <div></div>

            {{-- ── Divider ────────────────────────────────── --}}
            <div class="rv-section-divider">
                <span>Contenido</span>
            </div>

            {{-- ── Pregunta del Usuario ───────────────────── --}}
            <div class="rv-field rv-field-full">
                <label class="rv-label" for="input-pregunta">
                    Pregunta del Usuario <span class="req">*</span>
                    <span class="hint" id="hint-pregunta">0 / 300 caracteres</span>
                </label>
                <textarea class="rv-textarea" id="input-pregunta" name="pregunta"
                    maxlength="300" rows="4"
                    placeholder="Escribe la pregunta tal como la formularía un usuario. Ej: ¿Cuánto tarda mi pedido en llegar?"
                    oninput="onTextareaInput('pregunta', 300)"
                    onblur="validateTextarea('pregunta')">{{ $valPregunta }}</textarea>
                <div class="rv-textarea-foot">
                    <div class="rv-error-msg" id="err-pregunta">Ingresa la pregunta del usuario.</div>
                    <div class="rv-char-count" id="count-pregunta" style="margin-left:auto;"></div>
                </div>
            </div>

            {{-- ── Respuesta del Bot ──────────────────────── --}}
            <div class="rv-field rv-field-full">
                <label class="rv-label" for="input-respuesta">
                    Respuesta del Bot <span class="req">*</span>
                    <span class="hint" id="hint-respuesta">0 / 1000 caracteres</span>
                </label>
                <textarea class="rv-textarea rv-textarea-lg" id="input-respuesta" name="respuesta"
                    maxlength="1000" rows="6"
                    placeholder="Escribe la respuesta que el bot debe dar. Sé claro, concreto y amigable."
                    oninput="onTextareaInput('respuesta', 1000)"
                    onblur="validateTextarea('respuesta')">{{ $valRespuesta }}</textarea>
                <div class="rv-textarea-foot">
                    <div class="rv-error-msg" id="err-respuesta">Ingresa la respuesta del bot.</div>
                    <div class="rv-char-count" id="count-respuesta" style="margin-left:auto;"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="rv-form-foot">
        <span class="rv-foot-note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            Los campos con <strong>&nbsp;*&nbsp;</strong> son obligatorios
        </span>
        <div class="rv-foot-actions">
            <a href="{{ route('admin.faqs.gestionar') }}" class="btn btn-ghost">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                Regresar
            </a>
            <button type="button" class="btn btn-dark" onclick="guardarFaq()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                    <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                </svg>
                {{ $editando ? 'Guardar cambios' : 'Guardar FAQ' }}
            </button>
        </div>
    </div>
</div>

<div class="rv-toast" id="toast">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
    </svg>
    <span id="toast-msg"></span>
</div>

@push('scripts')
<script>
// ── Datos ────────────────────────────────────────────────────────────
const CATEGORIAS = @json($categorias);
let selectedCat  = @json($valCat);

// ── Autocomplete Categoría ───────────────────────────────────────────
function renderDropdown(query) {
    const drop = document.getElementById('drop-categoria');
    const q    = query.trim().toLowerCase();
    const list = q ? CATEGORIAS.filter(c => c.toLowerCase().includes(q)) : CATEGORIAS;
    if (!list.length) {
        drop.innerHTML = '<div class="rv-dropdown-empty">Sin resultados</div>';
        return;
    }
    drop.innerHTML = list.map(item => {
        const label = q ? item.replace(new RegExp(`(${q})`, 'gi'), '<mark>$1</mark>') : item;
        const safe  = item.replace(/'/g, "\\'");
        return `<div class="rv-dropdown-item" onmousedown="selectCategoria('${safe}')">${label}</div>`;
    }).join('');
}

function acOpen() {
    renderDropdown(document.getElementById('input-categoria').value);
    document.getElementById('drop-categoria').classList.add('open');
}
function acFilter() {
    renderDropdown(document.getElementById('input-categoria').value);
    document.getElementById('drop-categoria').classList.add('open');
    document.getElementById('hidden-categoria').value = '';
    selectedCat = '';
    hideBadge();
}
function acBlur() {
    setTimeout(() => {
        document.getElementById('drop-categoria').classList.remove('open');
        const v = document.getElementById('input-categoria').value;
        if (CATEGORIAS.includes(v)) {
            document.getElementById('hidden-categoria').value = v;
            selectedCat = v;
            showBadge(v);
        } else {
            document.getElementById('hidden-categoria').value = '';
            selectedCat = '';
            hideBadge();
        }
    }, 160);
}
function selectCategoria(value) {
    document.getElementById('input-categoria').value  = value;
    document.getElementById('hidden-categoria').value = value;
    selectedCat = value;
    document.getElementById('drop-categoria').classList.remove('open');
    document.getElementById('input-categoria').classList.remove('is-error');
    document.getElementById('err-categoria').classList.remove('visible');
    showBadge(value);
}
function showBadge(text) {
    const b = document.getElementById('badge-cat');
    document.getElementById('badge-cat-text').textContent = text;
    b.classList.add('show');
}
function hideBadge() {
    document.getElementById('badge-cat').classList.remove('show');
}

// ── Textareas ────────────────────────────────────────────────────────
function onTextareaInput(field, max) {
    const el  = document.getElementById('input-' + field);
    const len = el.value.length;
    const counter = document.getElementById('count-' + field);
    counter.textContent = len + ' / ' + max;
    counter.classList.toggle('warn', len >= max * 0.85);
    if (len > 0) {
        el.classList.remove('is-error');
        document.getElementById('err-' + field).classList.remove('visible');
    }
}

function validateTextarea(field) {
    const val = document.getElementById('input-' + field).value.trim();
    const ok  = val.length > 0;
    document.getElementById('input-' + field).classList.toggle('is-error', !ok);
    document.getElementById('err-'   + field).classList.toggle('visible',  !ok);
    return ok;
}

// ── Guardar ──────────────────────────────────────────────────────────
function guardarFaq() {
    let valid = true;

    // Validar categoría
    const catInput = document.getElementById('input-categoria').value;
    if (!CATEGORIAS.includes(catInput) || !selectedCat) {
        document.getElementById('input-categoria').classList.add('is-error');
        document.getElementById('err-categoria').classList.add('visible');
        valid = false;
    } else {
        document.getElementById('input-categoria').classList.remove('is-error');
        document.getElementById('err-categoria').classList.remove('visible');
    }

    // Validar pregunta y respuesta
    if (!validateTextarea('pregunta'))  valid = false;
    if (!validateTextarea('respuesta')) valid = false;

    if (!valid) return;

    const isEdit = {{ $editando ? 'true' : 'false' }};
    showToast(isEdit ? 'FAQ actualizada correctamente.' : 'FAQ guardada correctamente.');
}

function showToast(msg) {
    const t = document.getElementById('toast');
    document.getElementById('toast-msg').textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3200);
}

// ── Inicializar contadores ───────────────────────────────────────────
onTextareaInput('pregunta',  300);
onTextareaInput('respuesta', 1000);
</script>
@endpush

@endsection