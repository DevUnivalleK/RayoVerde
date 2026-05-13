@extends('layouts.admin-sidebar')

@section('title', 'Productos')
@section('breadcrumb', 'Productos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/estilosDash.css') }}">
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
    <div class="rv-hero-body">
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