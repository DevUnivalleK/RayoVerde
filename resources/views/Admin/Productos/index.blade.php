@extends('layouts.admin-sidebar')

@section('title', 'Productos')
@section('breadcrumb', 'Productos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/estilosDash.css') }}">
@endpush

@section('content')

@php
$total = $productos->count();
$disponibles = $productos->count();
$noDisp = 0;
@endphp

{{-- ── HERO ─────────────────────────────────────────── --}}
<div class="rv-hero">
    

    <div class="rv-hero-body">
        <h1>Gestionar <em>Productos</em></h1>
        <p>Administra el catálogo de aceites, precios e imágenes</p>
    </div>

    <div class="rv-stats">
        <div class="rv-stat">
            <div class="rv-stat-val">{{ $total }}</div>
            <div class="rv-stat-lbl">Productos registrados</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val"><span class="dot dot-on"></span>{{ $disponibles }}</div>
            <div class="rv-stat-lbl">Productos disponibles</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val"><span class="dot dot-off"></span>{{ $noDisp }}</div>
            <div class="rv-stat-lbl">No disponibles</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">0</div>
            <div class="rv-stat-lbl">Tipos de aceite</div>
        </div>
    </div>
</div>

{{-- ── Flash messages ───────────────────────────────── --}}
@if(session('success'))
    <div class="rv-flash rv-flash-success">
        ✓ {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="rv-flash rv-flash-error">
        ✗ {{ session('error') }}
    </div>
@endif

{{-- ── TOOLBAR ──────────────────────────────────────── --}}
<div class="rv-toolbar">
    <div class="rv-search">
        <img src="/images/icono-buscar.png" alt="" class="rv-search-icon">
        <input type="text" id="buscador" placeholder="Buscar producto, tipo de aceite..." oninput="filtrarTabla()">
    </div>
    <form method="GET" action="{{ route('admin.productos.index') }}" id="formFiltro">
</form>
</div>

{{-- ── TABLA ─────────────────────────────────────────── --}}
<div class="rv-card">
    <div class="rv-card-head">
        <div class="rv-card-title">
            <div class="rv-card-icon">
                <img src="/images/icono-producto.png" alt="">
            </div>
            Productos registrados
        </div>
        <span class="rv-badge" id="rv-badge">{{ $total }} registros</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Imagen</th>
                <th class="th-sortable" onclick="sortTable(1)">Nombre <span class="sort-icon">⇅</span></th>
                <th>Tipo de aceite</th>
                <th class="th-sortable" onclick="sortTable(3)">Precio (Bs.) <span class="sort-icon">⇅</span></th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaBody">
            @forelse($productos as $producto)
            <tr data-nombre="{{ strtolower($producto->nombre) }}"
                data-tipo="{{ strtolower($producto->tipo_aceite ?? '') }}">
                <td>
                    @if($producto->imagen_url)
                        <img src="{{ $producto->imagen_url }}"
                             alt="{{ $producto->nombre }}"
                             class="rv-thumb">
                    @else
                        <div class="rv-thumb-placeholder">
                            <img src="/images/icono-producto.png" alt="">
                        </div>
                    @endif
                </td>
                <td><strong>{{ $producto->nombre }}</strong></td>
                <td>
                    @if($producto->tipo_aceite)
                        <span class="tipo-tag">{{ $producto->tipo_aceite }}</span>
                    @else
                        <span class="muted">—</span>
                    @endif
                </td>
                <td class="price">{{ number_format($producto->precio, 2) }}</td>
                <td>
                    <span class="pill {{ $producto->disponible ? 'pill-on' : 'pill-off' }}">
                        {{ $producto->disponible ? 'Disponible' : 'No disponible' }}
                    </span>
                </td>
                <td>
                    <div class="td-actions">
                        <a href="{{ route('admin.productos.editar', $producto->id_producto) }}"
                           class="btn-icon btn-icon-edit" title="Editar">
                            <img src="/images/icono-editar.png" alt="Editar">
                        </a>
                        <button class="btn-icon btn-icon-delete" title="Eliminar"
                            onclick="confirmarEliminar('{{ $producto->nombre }}', {{ $producto->id_producto }}, this)">
                            <img src="/images/icono-basurero.png" alt="Eliminar">
                        </button>
                    </div>

                    {{-- Form oculto para DELETE --}}
                    <form id="form-delete-{{ $producto->id_producto }}"
                          action="{{ route('admin.productos.destroy', $producto->id_producto) }}"
                          method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="rv-empty">No hay productos registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div id="rv-empty" class="rv-empty" style="display:none;">
        Sin resultados para tu búsqueda.
    </div>
</div>

{{-- ── BOTONES ──────────────────────────────────────── --}}
<div class="rv-btn-row">
    <a href="{{ route('catalogo.index') }}" class="btn btn-ghost">
        <img src="/images/icono-region.png" alt="">
        Ver catálogo público
    </a>
    <a href="{{ route('admin.productos.crear') }}" class="btn btn-dark">
        <img src="/images/icono-agregar.png" alt="">
        Agregar Producto
    </a>
</div>

{{-- ── MODAL ELIMINAR ───────────────────────────────── --}}
<div class="rv-modal-overlay" id="modalEliminar">
    <div class="rv-modal">
        <div class="rv-modal-header">
            <div class="rv-modal-ico">
                <img src="/images/icono-basurero.png" alt="">
            </div>
            <div class="rv-modal-title">Eliminar producto</div>
        </div>
        <div class="rv-modal-body">
            <p>¿Estás seguro que deseas eliminar <strong id="modalNombre"></strong>?</p>
            <p style="margin-top:8px; color: var(--ink-muted);">Esta acción eliminará también su imagen y no se puede deshacer.</p>
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
    <span id="rv-toast-msg">Acción realizada.</span>
</div>

@push('scripts')
<script>
// ── Búsqueda en tiempo real ────────────────────────────
function filtrarTabla() {
    const texto = document.getElementById('buscador').value.toLowerCase();
    const filas = document.querySelectorAll('#tablaBody tr[data-nombre]');
    let visibles = 0;
    filas.forEach(fila => {
        const coincide =
            fila.dataset.nombre.includes(texto) ||
            fila.dataset.tipo.includes(texto) ||
            fila.innerText.toLowerCase().includes(texto);
        fila.style.display = coincide ? '' : 'none';
        if (coincide) visibles++;
    });
    document.getElementById('rv-empty').style.display = visibles === 0 ? 'block' : 'none';
}

// ── Ordenar ───────────────────────────────────────────
let sortDir = {};
function sortTable(col) {
    sortDir[col] = !sortDir[col];
    const tbody = document.getElementById('tablaBody');
    const filas = Array.from(tbody.querySelectorAll('tr[data-nombre]'));
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
let pendienteId   = null;
let pendienteFila = null;

function confirmarEliminar(nombre, id, btn) {
    pendienteId   = id;
    pendienteFila = btn.closest('tr');  // guardar ANTES de abrir modal
    document.getElementById('modalNombre').textContent = nombre;
    document.getElementById('modalEliminar').classList.add('active');
}

function cerrarModal() {
    document.getElementById('modalEliminar').classList.remove('active');
    // NO limpiar pendienteId ni pendienteFila aquí
}

function ejecutarEliminar() {
    if (!pendienteId) return;

    // Cerrar modal visualmente pero conservar las referencias
    document.getElementById('modalEliminar').classList.remove('active');

    // Animar fila si existe
    if (pendienteFila) {
        pendienteFila.style.transition = 'opacity .35s ease, transform .35s ease';
        pendienteFila.style.opacity    = '0';
        pendienteFila.style.transform  = 'translateX(20px)';
    }

    const formId = 'form-delete-' + pendienteId;
    const form   = document.getElementById(formId);

    if (!form) {
        console.error('No se encontró el form:', formId);
        return;
    }

    setTimeout(() => form.submit(), 370);

    // Limpiar después de disparar
    pendienteId   = null;
    pendienteFila = null;
}

document.getElementById('modalEliminar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModal(); });

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

// Mostrar toast si hay flash de éxito (post-redirect)
@if(session('success'))
    document.addEventListener('DOMContentLoaded', () => mostrarToast("{{ session('success') }}"));
@endif

document.getElementById('modalEliminar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModal(); });
</script>
@endpush

@endsection