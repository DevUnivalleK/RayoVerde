@extends('layout')
@section('title', isset($producto) ? 'Editar Producto — Rayo Verde' : 'Agregar Producto — Rayo Verde')

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
    --amber-100: #fdf0db;
    --amber-700: #633806;
    --ink:       #0d1f05;
    --ink-mid:   #3a4a30;
    --ink-muted: #7a8f6e;
    --border:    #dde8d0;
    --surface:   #f8faf4;
    --white:     #ffffff;
    --error:     #c0392b;
    --error-bg:  #fceaea;
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
.rv-flash-error { background: var(--error-bg); color: #7a1f1f; border: 1px solid #f5c2c2; }

/* ─── Layout de dos columnas ─────────────────────────── */
.rv-form-layout {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    align-items: start;
    margin-bottom: 16px;
}
@media (max-width: 860px) {
    .rv-form-layout { grid-template-columns: 1fr; }
}

/* ─── Cards base ─────────────────────────────────────── */
.rv-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-card);
    margin-bottom: 20px;
}
.rv-card:last-child { margin-bottom: 0; }

.rv-card-head {
    padding: 16px 24px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 10px;
    font-size: 14px; font-weight: 600; color: var(--ink); letter-spacing: -0.2px;
    border-radius: var(--radius-md) var(--radius-md) 0 0;
}
.rv-card-icon {
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    background: var(--green-100); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.rv-card-icon img { width: 18px; height: 18px; object-fit: contain; }
.rv-card-icon-amber { background: var(--amber-100); }

.rv-card-body { padding: 24px; }

/* ─── Form fields ────────────────────────────────────── */
.rv-field { margin-bottom: 20px; }
.rv-field:last-child { margin-bottom: 0; }

.rv-label {
    display: block; font-size: 11px; font-weight: 600;
    color: var(--ink-muted); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 7px;
}
.rv-label span { color: var(--error); margin-left: 2px; }

.rv-input {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-family: 'Sora', sans-serif; font-size: 13px; color: var(--ink);
    background: var(--white); outline: none; transition: border-color .2s, box-shadow .2s;
}
.rv-input:focus { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(79,144,32,0.1); }
.rv-input.is-error { border-color: var(--error); }

.rv-textarea {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-family: 'Sora', sans-serif; font-size: 13px; color: var(--ink);
    background: var(--white); outline: none; resize: vertical; min-height: 90px;
    transition: border-color .2s, box-shadow .2s;
}
.rv-textarea:focus { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(79,144,32,0.1); }

/* Precio con prefijo */
.rv-price-wrap {
    display: flex; align-items: center;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    overflow: hidden; transition: border-color .2s, box-shadow .2s; background: var(--white);
}
.rv-price-wrap:focus-within { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(79,144,32,0.1); }
.rv-price-wrap.is-error { border-color: var(--error); }
.rv-price-prefix {
    padding: 11px 14px; font-size: 12px; font-weight: 600;
    color: var(--ink-muted); background: var(--surface);
    border-right: 1.5px solid var(--border); white-space: nowrap; flex-shrink: 0;
}
.rv-price-input {
    flex: 1; border: none; outline: none; padding: 11px 14px;
    font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 600;
    color: var(--green-700); font-variant-numeric: tabular-nums; background: transparent;
}

/* Campo de cantidad */
.rv-qty-wrap {
    display: flex; align-items: center; gap: 0;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    overflow: hidden; background: var(--white);
    transition: border-color .2s, box-shadow .2s;
}
.rv-qty-wrap:focus-within { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(79,144,32,0.1); }
.rv-qty-btn {
    width: 40px; height: 44px; border: none;
    background: var(--surface); color: var(--ink-mid);
    font-size: 18px; cursor: pointer; flex-shrink: 0;
    transition: background .15s;
    display: flex; align-items: center; justify-content: center;
}
.rv-qty-btn:hover { background: var(--green-100); color: var(--green-700); }
.rv-qty-input {
    flex: 1; border: none; outline: none; padding: 11px 8px;
    font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 600;
    color: var(--ink); text-align: center; background: transparent;
    font-variant-numeric: tabular-nums;
}
.rv-qty-suffix {
    padding: 11px 14px; font-size: 12px; color: var(--ink-muted);
    background: var(--surface); border-left: 1.5px solid var(--border); flex-shrink: 0;
}

/* Error msg */
.rv-error-msg { font-size: 11px; color: var(--error); margin-top: 5px; display: none; }
.rv-error-msg.visible { display: block; }

/* ─── Motivo cambio (solo aparece si precio cambia) ───── */
.rv-motivo-wrap {
    margin-top: 14px; padding: 14px;
    background: var(--amber-100); border-radius: var(--radius-sm);
    border: 1px solid #f0d9b0;
    display: none;
}
.rv-motivo-wrap.visible { display: block; }
.rv-motivo-label {
    font-size: 11px; font-weight: 600; color: var(--amber-700);
    letter-spacing: 1px; text-transform: uppercase; margin-bottom: 7px;
    display: flex; align-items: center; gap: 6px;
}

/* ─── Zona de imagen ─────────────────────────────────── */
.rv-img-drop {
    border: 2px dashed var(--border);
    border-radius: var(--radius-md);
    padding: 28px 20px;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    background: var(--surface);
    position: relative;
}
.rv-img-drop:hover, .rv-img-drop.dragover {
    border-color: var(--green-500);
    background: var(--green-50);
}
.rv-img-drop input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.rv-img-drop-icon { width: 40px; height: 40px; object-fit: contain; opacity: 0.35; margin-bottom: 10px; }
.rv-img-drop p { font-size: 13px; color: var(--ink-muted); }
.rv-img-drop strong { color: var(--green-600); }
.rv-img-drop small { font-size: 11px; color: var(--ink-muted); display: block; margin-top: 4px; }

/* Preview imagen */
.rv-img-preview {
    width: 100%; aspect-ratio: 1;
    border-radius: var(--radius-md); object-fit: cover;
    border: 1px solid var(--border); display: none;
    margin-bottom: 10px;
}
.rv-img-preview.visible { display: block; }
.rv-img-remove {
    width: 100%; padding: 7px; border-radius: var(--radius-sm);
    background: var(--error-bg); color: var(--error);
    border: 1px solid #f5c2c2; font-family: 'Sora', sans-serif;
    font-size: 11px; font-weight: 600; cursor: pointer;
    transition: all .17s; display: none;
}
.rv-img-remove.visible { display: block; }
.rv-img-remove:hover { background: #f5c2c2; }

/* URL actual */
.rv-img-current {
    margin-bottom: 14px;
}
.rv-img-current img {
    width: 100%; aspect-ratio: 1; object-fit: cover;
    border-radius: var(--radius-md); border: 1px solid var(--border);
    margin-bottom: 6px;
}
.rv-img-url {
    font-size: 10px; color: var(--ink-muted);
    word-break: break-all; padding: 6px 10px;
    background: var(--surface); border-radius: 6px;
    border: 1px solid var(--border);
}

/* ─── Historial de precios ───────────────────────────── */
.rv-historial-empty {
    padding: 24px; text-align: center;
    color: var(--ink-muted); font-size: 12px;
}
.rv-historial-row {
    padding: 14px 24px;
    border-bottom: 1px solid #f0f4eb;
    display: flex; align-items: center; gap: 14px;
}
.rv-historial-row:last-child { border-bottom: none; }
.rv-hist-arrow {
    display: flex; align-items: center; gap: 6px; flex: 1;
}
.rv-hist-precio-old {
    font-size: 13px; color: var(--ink-muted);
    text-decoration: line-through; font-variant-numeric: tabular-nums;
}
.rv-hist-arrow-icon { font-size: 12px; color: var(--ink-muted); }
.rv-hist-precio-new {
    font-size: 13px; font-weight: 700;
    color: var(--green-700); font-variant-numeric: tabular-nums;
}
.rv-hist-meta { text-align: right; flex-shrink: 0; }
.rv-hist-fecha { font-size: 11px; color: var(--ink-muted); }
.rv-hist-motivo {
    font-size: 10px; color: var(--amber-700);
    background: var(--amber-100); padding: 2px 7px;
    border-radius: 10px; margin-top: 3px; display: inline-block;
}

/* ─── Footer / botones ───────────────────────────────── */
.rv-form-foot {
    display: flex; justify-content: space-between; align-items: center; gap: 12px;
    padding: 16px 0;
}
.btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: var(--radius-sm);
    font-size: 12px; font-weight: 600; border: none; cursor: pointer;
    text-decoration: none; transition: all .18s ease;
    letter-spacing: 0.2px; font-family: 'Sora', sans-serif;
}
.btn img { width: 14px; height: 14px; object-fit: contain; }
.btn-dark { background: var(--green-600); color: #fff; }
.btn-dark:hover { background: var(--green-700); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(59,109,17,0.3); }
.btn-ghost { background: transparent; color: var(--ink-mid); border: 1.5px solid var(--border); }
.btn-ghost:hover { background: var(--green-100); border-color: var(--green-500); color: var(--green-700); }
</style>
@endpush

@section('content')

@php
$editando      = isset($producto);
$valNombre     = $editando ? $producto->nombre        : '';
$valPrecio     = $editando ? $producto->precio        : '';
$valCantidad   = $editando ? $producto->cantidad      : 1;
$valImagenUrl  = $editando ? $producto->imagen_url    : '';
$valDescripcion= $editando ? ($producto->descripcion ?? '') : '';
$precioOriginal= $editando ? (float) $producto->precio : 0;
@endphp

{{-- ── HERO ─────────────────────────────────────────── --}}
<div class="rv-hero">
    <div class="rv-hero-top">
        <div class="rv-logo"><img src="/images/logo.png" alt="Rayo Verde"></div>
        <div>
            <div class="rv-brand-name">Rayo Verde</div>
            <div class="rv-brand-sub">Panel Administrativo</div>
        </div>
    </div>
    <div class="rv-hero-body">
        <div class="rv-hero-eyebrow">Catálogo</div>
        <h1>{{ $editando ? 'Editar' : 'Agregar' }} <em>Producto</em></h1>
        <p>{{ $editando ? 'Modifica los datos, precio e imagen del producto' : 'Completa el formulario para registrar un nuevo producto en el catálogo' }}</p>
    </div>
</div>

{{-- Flash errores de validación --}}
@if($errors->any())
<div class="rv-flash rv-flash-error">
    ✗ {{ $errors->first() }}
</div>
@endif

{{-- ── FORMULARIO ───────────────────────────────────── --}}
<form action="{{ $editando ? route('admin.productos.update', $producto->id_producto) : route('admin.productos.store') }}"
      method="POST"
      enctype="multipart/form-data"
      id="formProducto">
    @csrf
    @if($editando) @method('PUT') @endif

    {{-- Precio original oculto para detectar cambios --}}
    <input type="hidden" id="precio-original" value="{{ $precioOriginal }}">

    <div class="rv-form-layout">

        {{-- ── Columna izquierda: datos principales ── --}}
        <div>
            <div class="rv-card">
                <div class="rv-card-head">
                    <div class="rv-card-icon"><img src="/images/icono-producto.png" alt=""></div>
                    Información del producto
                </div>
                <div class="rv-card-body">

                    {{-- Nombre --}}
                    <div class="rv-field">
                        <label class="rv-label" for="nombre">Nombre del producto <span>*</span></label>
                        <input type="text" id="nombre" name="nombre" class="rv-input @error('nombre') is-error @enderror"
                               placeholder="Ej: Aceite de Coco (1 L.)"
                               value="{{ old('nombre', $valNombre) }}" required>
                        @error('nombre')
                            <div class="rv-error-msg visible">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="rv-field">
                        <label class="rv-label" for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion"
                                  class="rv-textarea"
                                  placeholder="Breve descripción del producto...">{{ old('descripcion', $valDescripcion) }}</textarea>
                    </div>

                    {{-- Precio --}}
                    <div class="rv-field">
                        <label class="rv-label" for="precio">Precio <span>*</span></label>
                        <div class="rv-price-wrap @error('precio') is-error @enderror" id="wrap-precio">
                            <span class="rv-price-prefix">Bs.</span>
                            <input type="number" id="precio" name="precio"
                                   class="rv-price-input"
                                   placeholder="0.00" step="0.01" min="0"
                                   value="{{ old('precio', $valPrecio) }}"
                                   oninput="detectarCambioPrecio()" required>
                        </div>
                        @error('precio')
                            <div class="rv-error-msg visible">{{ $message }}</div>
                        @enderror

                        {{-- Motivo del cambio (BE-05) — aparece solo si el precio cambia en edición --}}
                        @if($editando)
                        <div class="rv-motivo-wrap" id="motivo-wrap">
                            <div class="rv-motivo-label">⚠ Motivo del cambio de precio</div>
                            <input type="text" name="motivo_cambio" id="motivo_cambio"
                                   class="rv-input"
                                   placeholder="Ej: Ajuste por inflación, promoción especial...">
                        </div>
                        @endif
                    </div>

                    {{-- Cantidad --}}
                    <div class="rv-field">
                        <label class="rv-label" for="cantidad">Stock disponible <span>*</span></label>
                        <div class="rv-qty-wrap">
                            <button type="button" class="rv-qty-btn" onclick="cambiarCantidad(-1)">−</button>
                            <input type="number" id="cantidad" name="cantidad"
                                   class="rv-qty-input"
                                   value="{{ old('cantidad', $valCantidad) }}"
                                   min="0" required>
                            <span class="rv-qty-suffix">unidades</span>
                            <button type="button" class="rv-qty-btn" onclick="cambiarCantidad(1)">+</button>
                        </div>
                        @error('cantidad')
                            <div class="rv-error-msg visible">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Historial de precios (BE-05, solo en edición) ── --}}
            @if($editando)
            <div class="rv-card">
                <div class="rv-card-head">
                    <div class="rv-card-icon rv-card-icon-amber">
                        <img src="/images/icono-envio.png" alt="">
                    </div>
                    Historial de precios
                </div>

                @if($historial->isEmpty())
                    <div class="rv-historial-empty">Sin cambios de precio registrados aún.</div>
                @else
                    @foreach($historial as $h)
                    <div class="rv-historial-row">
                        <div class="rv-hist-arrow">
                            <span class="rv-hist-precio-old">Bs. {{ number_format($h->precio_anterior, 2) }}</span>
                            <span class="rv-hist-arrow-icon">→</span>
                            <span class="rv-hist-precio-new">Bs. {{ number_format($h->precio_nuevo, 2) }}</span>
                        </div>
                        <div class="rv-hist-meta">
                            <div class="rv-hist-fecha">{{ \Carbon\Carbon::parse($h->cambiado_en)->format('d/m/Y H:i') }}</div>
                            @if($h->motivo)
                                <div class="rv-hist-motivo">{{ $h->motivo }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
            @endif
        </div>

        {{-- ── Columna derecha: imagen ── --}}
        <div>
            <div class="rv-card">
                <div class="rv-card-head">
                    <div class="rv-card-icon"><img src="/images/icono-region.png" alt=""></div>
                    Imagen del producto
                </div>
                <div class="rv-card-body">

                    {{-- Imagen actual (edición) --}}
                    @if($editando && $valImagenUrl)
                    <div class="rv-img-current">
                        <img src="{{ $valImagenUrl }}" alt="{{ $valNombre }}" id="imgActual">
                        <div class="rv-img-url">{{ $valImagenUrl }}</div>
                    </div>
                    @endif

                    {{-- Preview de nueva imagen --}}
                    <img id="imgPreview" class="rv-img-preview" src="#" alt="Preview">

                    {{-- Zona drag & drop --}}
                    <div class="rv-img-drop" id="imgDrop">
                        <input type="file" name="imagen" id="imagen"
                               accept=".jpg,.jpeg,.png"
                               onchange="previewImagen(this)">
                        <img src="/images/icono-agregar.png" alt="" class="rv-img-drop-icon">
                        <p><strong>Haz clic o arrastra</strong> una imagen aquí</p>
                        <small>JPG, JPEG o PNG · Máx. 2 MB</small>
                    </div>
                    @error('imagen')
                        <div class="rv-error-msg visible" style="margin-top:6px;">{{ $message }}</div>
                    @enderror

                    <button type="button" id="btnRemove" class="rv-img-remove" onclick="removerImagen()">
                        ✕ Quitar imagen seleccionada
                    </button>

                    {{-- URL externa (alternativa) --}}
                    <div style="margin-top:16px;">
                        <label class="rv-label" for="imagen_url">O pega una URL de imagen</label>
                        <input type="text" id="imagen_url" name="imagen_url"
                               class="rv-input"
                               placeholder="https://res.cloudinary.com/..."
                               value="{{ old('imagen_url', $valImagenUrl) }}"
                               oninput="previewUrl(this.value)">
                    </div>

                </div>
            </div>
        </div>

    </div>{{-- /rv-form-layout --}}

    {{-- ── Botones ──────────────────────────────────── --}}
    <div class="rv-form-foot">
        <a href="{{ route('admin.productos.index') }}" class="btn btn-ghost">
            <img src="/images/icono-regresar.png" alt="">
            Regresar
        </a>
        <button type="submit" class="btn btn-dark">
            <img src="/images/icono-agregar.png" alt="">
            {{ $editando ? 'Guardar cambios' : 'Guardar Producto' }}
        </button>
    </div>

</form>

@push('scripts')
<script>
// ── Detectar cambio de precio (BE-05) ─────────────────
const precioOriginal = parseFloat(document.getElementById('precio-original')?.value || 0);

function detectarCambioPrecio() {
    const motivoWrap = document.getElementById('motivo-wrap');
    if (!motivoWrap) return;
    const actual = parseFloat(document.getElementById('precio').value || 0);
    if (actual !== precioOriginal && actual > 0) {
        motivoWrap.classList.add('visible');
    } else {
        motivoWrap.classList.remove('visible');
    }
}

// ── Cantidad stepper ──────────────────────────────────
function cambiarCantidad(delta) {
    const input = document.getElementById('cantidad');
    const nuevo = Math.max(0, parseInt(input.value || 0) + delta);
    input.value = nuevo;
}

// ── Preview imagen seleccionada ───────────────────────
function previewImagen(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    // Validar tipo
    if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
        alert('Solo se permiten imágenes JPG o PNG.');
        input.value = '';
        return;
    }
    // Validar tamaño (2 MB)
    if (file.size > 2 * 1024 * 1024) {
        alert('La imagen no debe superar los 2 MB.');
        input.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('imgPreview').src = e.target.result;
        document.getElementById('imgPreview').classList.add('visible');
        document.getElementById('btnRemove').classList.add('visible');
        document.getElementById('imgDrop').style.display = 'none';
        // Limpiar URL manual si se sube archivo
        document.getElementById('imagen_url').value = '';
    };
    reader.readAsDataURL(file);
}

function removerImagen() {
    document.getElementById('imagen').value = '';
    document.getElementById('imgPreview').src = '#';
    document.getElementById('imgPreview').classList.remove('visible');
    document.getElementById('btnRemove').classList.remove('visible');
    document.getElementById('imgDrop').style.display = '';
}

// ── Preview URL externa ───────────────────────────────
function previewUrl(url) {
    if (!url) return;
    const preview = document.getElementById('imgPreview');
    preview.src = url;
    preview.classList.add('visible');
    document.getElementById('btnRemove').classList.add('visible');
    document.getElementById('imgDrop').style.display = 'none';
    // Limpiar archivo si se pone URL
    document.getElementById('imagen').value = '';
}

// ── Drag & drop visual ────────────────────────────────
const drop = document.getElementById('imgDrop');
drop.addEventListener('dragover', e => { e.preventDefault(); drop.classList.add('dragover'); });
drop.addEventListener('dragleave', ()  => drop.classList.remove('dragover'));
drop.addEventListener('drop', e => {
    e.preventDefault(); drop.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('imagen').files = dt.files;
        previewImagen(document.getElementById('imagen'));
    }
});
</script>
@endpush

@endsection