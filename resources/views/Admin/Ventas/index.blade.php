@extends('layout')
@section('title', 'Ventas y Pagos — Rayo Verde')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/estilosDash.css') }}">
@endpush

@section('content')

@php
// Datos calculados desde el controlador
$totalMonto    = $ventas->sum('monto');
$totalRegistros= $ventas->count();
$ventaHoy      = $ventas->filter(fn($v) => \Carbon\Carbon::parse($v->fecha)->isToday())->sum('monto');
$productoTop   = $ventas->groupBy('id_producto')->map->sum('monto')->sortDesc()->first();
@endphp

{{-- ── HERO ─────────────────────────────────────────── --}}
<div class="rv-hero">
    <div class="rv-hero-body">
        <h1>Ventas y <em>Pagos</em></h1>
        <p>Registro de ventas por producto y seguimiento de ingresos</p>
    </div>
    <div class="rv-stats">
        <div class="rv-stat">
            <div class="rv-stat-val">{{ $totalRegistros }}</div>
            <div class="rv-stat-lbl">Ventas registradas</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">Bs. {{ number_format($totalMonto, 2) }}</div>
            <div class="rv-stat-lbl">Ingresos totales</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">Bs. {{ number_format($ventaHoy, 2) }}</div>
            <div class="rv-stat-lbl">Ingresos hoy</div>
        </div>
        <div class="rv-stat">
            <div class="rv-stat-val">Bs. {{ $totalRegistros > 0 ? number_format($totalMonto / $totalRegistros, 2) : '0.00' }}</div>
            <div class="rv-stat-lbl">Promedio por venta</div>
        </div>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="rv-flash rv-flash-success">✓ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="rv-flash rv-flash-error">✗ {{ session('error') }}</div>
@endif

{{-- ── LAYOUT PRINCIPAL ─────────────────────────────── --}}
<div class="rv-main-grid">

    {{-- ── Columna izquierda: tabla de ventas ── --}}
    <div>
        {{-- Toolbar --}}
        <div class="rv-toolbar">
            <div class="rv-search">
                <img src="/images/icono-buscar.png" alt="" class="rv-search-icon">
                <input type="text" id="buscador" placeholder="Buscar por producto o fecha..." oninput="filtrarTabla()">
            </div>
        </div>

        <div class="rv-card">
            <div class="rv-card-head">
                <div class="rv-card-title">
                    <div class="rv-card-icon"><img src="/images/icono-envio.png" alt=""></div>
                    Historial de ventas
                </div>
                <span class="rv-badge" id="rv-badge">{{ $totalRegistros }} registros</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="th-sortable" onclick="sortTable(1)">Nombre <span class="sort-icon">⇅</span></th>
                        <th class="th-sortable" onclick="sortTable(2)">Monto (Bs.) <span class="sort-icon">⇅</span></th>
                        <th class="th-sortable" onclick="sortTable(3)">Fecha <span class="sort-icon">⇅</span></th>
                    </tr>
                </thead>
                <tbody id="tablaBody">
                    @forelse($ventas as $venta)
                    <tr>
                        <td>
                            @if($venta->producto && $venta->producto->imagen_url)
                                <img src="{{ $venta->producto->imagen_url }}"
                                     alt="{{ $venta->producto->nombre }}"
                                     class="rv-thumb">
                            @else
                                <div class="rv-thumb-placeholder">
                                    <img src="/images/icono-producto.png" alt="">
                                </div>
                            @endif
                        </td>
                        <td><strong>{{ $venta->producto->nombre ?? '—' }}</strong></td>
                        <td class="price">{{ number_format($venta->monto, 2) }}</td>
                        <td class="muted">
                            {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="rv-empty">No hay ventas registradas aún.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Total al pie de tabla --}}
            @if($ventas->isNotEmpty())
            <div style="padding: 14px 18px; border-top: 1px solid var(--border); background: var(--surface); display: flex; justify-content: flex-end; align-items: center; gap: 12px;">
                <span style="font-size: 12px; color: var(--ink-muted); font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;">Total acumulado</span>
                <span class="price-total">Bs. {{ number_format($totalMonto, 2) }}</span>
            </div>
            @endif

            <div id="rv-empty-msg" class="rv-empty" style="display:none;">Sin resultados para tu búsqueda.</div>
        </div>
    </div>

    {{-- ── Columna derecha: registrar nueva venta ── --}}
    <div>
        <div class="rv-form-card">
            <div class="rv-form-head">
                <div class="rv-card-icon rv-card-icon-amber">
                    <img src="/images/icono-agregar.png" alt="">
                </div>
                Registrar venta
            </div>

            <form action="{{ route('admin.ventas.store') }}" method="POST" id="formVenta">
                @csrf
                <div class="rv-form-body">

                    {{-- Producto --}}
                    <div class="rv-field">
                        <label class="rv-label" for="id_producto">Producto <span>*</span></label>
                        <select name="id_producto" id="id_producto" class="rv-select"
                                onchange="mostrarPrecio(this)" required>
                            <option value="">— Seleccionar producto —</option>
                            @foreach($productos as $p)
                                <option value="{{ $p->id_producto }}"
                                        data-precio="{{ $p->precio }}"
                                        data-stock="{{ $p->cantidad }}">
                                    {{ $p->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="rv-precio-preview" id="precioPreview">
                            Precio unitario: <strong id="precioValor">—</strong>
                            &nbsp;·&nbsp; Stock: <strong id="stockValor">—</strong>
                        </div>
                    </div>

                    {{-- Monto --}}
                    <div class="rv-field">
                        <label class="rv-label" for="monto">Monto cobrado <span>*</span></label>
                        <div class="rv-price-wrap">
                            <span class="rv-price-prefix">Bs.</span>
                            <input type="number" id="monto" name="monto"
                                   class="rv-price-input"
                                   placeholder="0.00" step="0.01" min="0.01"
                                   value="{{ old('monto') }}" required>
                        </div>
                        @error('monto')
                            <div style="font-size:11px;color:#c0392b;margin-top:5px;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Fecha --}}
                    <div class="rv-field">
                        <label class="rv-label" for="fecha">Fecha <span>*</span></label>
                        <input type="datetime-local" id="fecha" name="fecha"
                               class="rv-input"
                               value="{{ old('fecha', now()->format('Y-m-d\TH:i')) }}" required>
                        @error('fecha')
                            <div style="font-size:11px;color:#c0392b;margin-top:5px;">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="rv-form-foot">
                    <button type="submit" class="btn btn-dark">
                        <img src="/images/icono-agregar.png" alt="">
                        Registrar venta
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>{{-- /rv-main-grid --}}

{{-- Toast --}}
<div id="rv-toast">
    <span id="rv-toast-msg">Venta registrada.</span>
</div>

@push('scripts')
<script>
// ── Mostrar precio y stock del producto seleccionado ──
function mostrarPrecio(sel) {
    const opt     = sel.options[sel.selectedIndex];
    const preview = document.getElementById('precioPreview');
    if (!opt.value) { preview.classList.remove('visible'); return; }
    document.getElementById('precioValor').textContent = 'Bs. ' + parseFloat(opt.dataset.precio).toFixed(2);
    document.getElementById('stockValor').textContent  = opt.dataset.stock + ' u.';
    // Sugerir el precio como monto por defecto
    document.getElementById('monto').value = parseFloat(opt.dataset.precio).toFixed(2);
    preview.classList.add('visible');
}

// ── Búsqueda en tiempo real ───────────────────────────
function filtrarTabla() {
    const texto = document.getElementById('buscador').value.toLowerCase();
    const filas = document.querySelectorAll('#tablaBody tr');
    let visibles = 0;
    filas.forEach(fila => {
        const coincide = fila.innerText.toLowerCase().includes(texto);
        fila.style.display = coincide ? '' : 'none';
        if (coincide) visibles++;
    });
    document.getElementById('rv-empty-msg').style.display = visibles === 0 ? 'block' : 'none';
}

// ── Ordenar tabla ─────────────────────────────────────
let sortDir = {};
function sortTable(col) {
    sortDir[col] = !sortDir[col];
    const tbody = document.getElementById('tablaBody');
    const filas = Array.from(tbody.querySelectorAll('tr')).filter(r => r.cells.length > 1);
    filas.sort((a, b) => {
        const va = a.cells[col].innerText.trim();
        const vb = b.cells[col].innerText.trim();
        const na = parseFloat(va), nb = parseFloat(vb);
        const cmp = (!isNaN(na) && !isNaN(nb)) ? na - nb : va.localeCompare(vb, 'es');
        return sortDir[col] ? cmp : -cmp;
    });
    filas.forEach(f => tbody.appendChild(f));
}

// ── Toast al volver de redirect con flash ─────────────
@if(session('success'))
    document.addEventListener('DOMContentLoaded', () => {
        const t = document.getElementById('rv-toast');
        document.getElementById('rv-toast-msg').textContent = "{{ session('success') }}";
        t.style.opacity = '1'; t.style.transform = 'translateY(0)';
        setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateY(16px)'; }, 3200);
    });
@endif
</script>
@endpush

@endsection