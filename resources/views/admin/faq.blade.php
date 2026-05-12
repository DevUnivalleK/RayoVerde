@extends('layout')
@section('title', 'Gestión de FAQ — Rayo Verde')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush

@section('content')

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
        <div class="rv-hero-eyebrow">Soporte</div>
        <h1>Preguntas <em>Frecuentes</em></h1>
        <p>Gestiona las respuestas automáticas del chatbot</p>
    </div>
</div>

{{-- ── FLASH ────────────────────────────────────────── --}}
@if(session('success'))
    <div class="rv-flash rv-flash-success">✓ {{ session('success') }}</div>
@endif

{{-- ── TOOLBAR ──────────────────────────────────────── --}}
<div class="rv-toolbar">
    <button class="btn btn-dark"
            onclick="document.getElementById('modalFaq').classList.add('active')">
        + Nueva Pregunta
    </button>
</div>

{{-- ── TABLA ────────────────────────────────────────── --}}
<div class="rv-card">
    <div class="rv-card-head">
        <div class="rv-card-title">
            <div class="rv-card-icon"><img src="/images/icono-faq.png" alt=""></div>
            FAQ registradas
        </div>
        <span class="rv-badge">{{ $faqs->count() }} preguntas</span>
    </div>

    @if($faqs->isEmpty())
        <div class="rv-empty">No hay preguntas registradas aún.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Pregunta</th>
                    <th>Respuesta</th>
                    <th style="text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($faqs as $faq)
                <tr>
                    <td><span class="tipo-tag">{{ $faq->categoria }}</span></td>
                    <td style="font-weight:600;">{{ $faq->pregunta }}</td>
                    <td class="muted" style="max-width:300px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $faq->respuesta }}</td>
                    <td>
                        <div class="td-actions" style="justify-content:center;">

                            {{-- Editar --}}
                            <button type="button" class="btn-icon btn-icon-edit"
                                    onclick="prepararEdicion({{ json_encode($faq) }})">
                                <img src="/images/icono-editar.png" alt="Editar">
                            </button>

                            {{-- Eliminar --}}
                            <form action="{{ route('admin.faq.destroy', $faq->id_faq) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta pregunta?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-delete">
                                    <img src="/images/icono-basurero.png" alt="Eliminar">
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>


{{-- ── MODAL: Agregar FAQ ───────────────────────────── --}}
<div class="rv-modal-overlay" id="modalFaq">
    <div class="rv-modal">
        <form action="{{ route('admin.faq.store') }}" method="POST">
            @csrf
            <div class="rv-modal-header">
                <div class="rv-modal-ico rv-modal-ico-ok">
                    <img src="/images/icono-agregar.png" alt="">
                </div>
                <div class="rv-modal-title">Agregar FAQ</div>
            </div>

            <div class="rv-modal-body">
                <div class="rv-field">
                    <label class="rv-label" for="categoria">Categoría</label>
                    <select name="categoria" id="categoria" class="rv-select rv-select--custom" required>
                        <option value="General">General</option>
                        <option value="Pedidos">Pedidos</option>
                        <option value="Pagos">Pagos</option>
                        <option value="Envíos">Envíos</option>
                    </select>
                </div>
                <div class="rv-field">
                    <label class="rv-label" for="pregunta">Pregunta</label>
                    <input type="text" name="pregunta" id="pregunta"
                           class="rv-input" placeholder="¿Cómo comprar?" required>
                </div>
                <div class="rv-field">
                    <label class="rv-label" for="respuesta">Respuesta</label>
                    <textarea name="respuesta" id="respuesta"
                              class="rv-textarea" rows="4"
                              placeholder="Describe la solución..." required></textarea>
                </div>
            </div>

            <div class="rv-modal-divider"></div>
            <div class="rv-modal-actions">
                <button type="button" class="btn-modal-cancel"
                        onclick="document.getElementById('modalFaq').classList.remove('active')">
                    Cancelar
                </button>
                <button type="submit" class="btn-modal-ok ok">Guardar Pregunta</button>
            </div>
        </form>
    </div>
</div>


{{-- ── MODAL: Editar FAQ ────────────────────────────── --}}
<div class="rv-modal-overlay" id="modalEditFaq">
    <div class="rv-modal">
        <form id="formEditFaq" method="POST">
            @csrf
            @method('PUT')
            <div class="rv-modal-header">
                <div class="rv-modal-ico rv-modal-ico-ok">
                    <img src="/images/icono-editar.png" alt="">
                </div>
                <div class="rv-modal-title">Editar FAQ</div>
            </div>

            <div class="rv-modal-body">
                <div class="rv-field">
                    <label class="rv-label" for="edit_categoria">Categoría</label>
                    <select name="categoria" id="edit_categoria" class="rv-select rv-select--custom" required>
                        <option value="General">General</option>
                        <option value="Pedidos">Pedidos</option>
                        <option value="Pagos">Pagos</option>
                        <option value="Envíos">Envíos</option>
                    </select>
                </div>
                <div class="rv-field">
                    <label class="rv-label" for="edit_pregunta">Pregunta</label>
                    <input type="text" name="pregunta" id="edit_pregunta"
                           class="rv-input" required>
                </div>
                <div class="rv-field">
                    <label class="rv-label" for="edit_respuesta">Respuesta</label>
                    <textarea name="respuesta" id="edit_respuesta"
                              class="rv-textarea" rows="4" required></textarea>
                </div>
            </div>

            <div class="rv-modal-divider"></div>
            <div class="rv-modal-actions">
                <button type="button" class="btn-modal-cancel"
                        onclick="document.getElementById('modalEditFaq').classList.remove('active')">
                    Cancelar
                </button>
                <button type="submit" class="btn-modal-ok ok">Actualizar Cambios</button>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<script>
// ── Preparar edición (igual que antes) ────────────────
function prepararEdicion(faq) {
    const form = document.getElementById('formEditFaq');
    form.action = `/admin/faq/${faq.id_faq}`;
    document.getElementById('edit_categoria').value = faq.categoria;
    document.getElementById('edit_pregunta').value  = faq.pregunta;
    document.getElementById('edit_respuesta').value = faq.respuesta;
    document.getElementById('modalEditFaq').classList.add('active');
}

// ── Cerrar con click fuera o Escape ───────────────────
['modalFaq', 'modalEditFaq'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('active');
    });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.getElementById('modalFaq').classList.remove('active');
        document.getElementById('modalEditFaq').classList.remove('active');
    }
});
</script>
@endpush

@endsection
