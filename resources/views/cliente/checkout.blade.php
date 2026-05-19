@extends('layout')
@section('title', 'Checkout — Rayo Verde')

@push('styles')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/estilosPago.css') }}">
@endpush

@section('content')

{{-- ── HERO ─────────────────────────────────────────── --}}
<div class="rv-hero">
    <div class="rv-hero-top">
        <div class="rv-logo"><img src="/images/logo.png" alt="Rayo Verde"></div>
        <div>
            <div class="rv-brand-name">Rayo Verde</div>
            <div class="rv-brand-sub">Aceites Naturales</div>
        </div>
    </div>
    <div class="rv-hero-body">
        <div class="rv-hero-eyebrow">Pago</div>
        <h1>Datos de <em>Pago</em></h1>
        <p>Ingresa los datos de la cuenta desde la que realizarás la transferencia</p>
    </div>
</div>

{{-- Pasos --}}
<div class="rv-steps">
    <div class="rv-step done">
        <span class="rv-step-num">✓</span>
        Carrito
    </div>
    <div class="rv-step-line"></div>
    <div class="rv-step active">
        <span class="rv-step-num">2</span>
        Datos de pago
    </div>
    <div class="rv-step-line"></div>
    <div class="rv-step">
        <span class="rv-step-num">3</span>
        Pagar con QR
    </div>
    <div class="rv-step-line"></div>
    <div class="rv-step">
        <span class="rv-step-num">4</span>
        Confirmación
    </div>
</div>

{{-- Flash errores de validación --}}
@if($errors->any())
    <div class="rv-flash-error">⚠ {{ $errors->first() }}</div>
@endif

<div class="rv-checkout-layout">

    {{-- ── Formulario ── --}}
    <div>
        <div class="rv-card">
            <div class="rv-card-head">
                <div class="rv-card-icon rv-card-icon-amber">
                    <img src="/images/icono-agregar.png" alt="">
                </div>
                Información del pagador
            </div>
            <div class="rv-card-body">
                <form action="{{ route('cliente.checkout.pagar') }}" method="POST" id="formCheckout">
                    @csrf

                    {{-- Nombre titular --}}
                    <div class="rv-field">
                        <label class="rv-label" for="nombre_titular">
                            Nombre completo del titular de la cuenta <span>*</span>
                        </label>
                        <input type="text" id="nombre_titular" name="nombre_titular"
                               class="rv-input @error('nombre_titular') is-error @enderror"
                               placeholder="Ej: Juan Carlos Mamani Quispe"
                               value="{{ old('nombre_titular') }}"
                               oninput="validarTitular(this)"
                               autocomplete="off"
                               required>

                        {{-- Alerta de advertencia --}}
                        <div class="rv-alerta-titular" id="alertaTitular">
                            <span class="icono">⚠</span>
                            <span>Por favor ingresa tu nombre tal como aparece en tu cuenta bancaria.
                            Un nombre incorrecto o incompleto puede <strong>extender el tiempo de verificación</strong> de tu pago.</span>
                        </div>

                        @error('nombre_titular')
                            <div class="rv-error-msg visible">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Banco --}}
                    <div class="rv-field">
                        <label class="rv-label" for="banco">
                            Banco o billetera digital desde la que pagas <span>*</span>
                        </label>
                        <select id="banco" name="banco"
                                class="rv-select @error('banco') is-error @enderror"
                                required>
                            <option value="">— Selecciona una opción —</option>
                            <optgroup label="Bancos">
                                <option value="Banco Unión" {{ old('banco') === 'Banco Unión' ? 'selected' : '' }}>Banco Unión</option>
                                <option value="Banco BCP" {{ old('banco') === 'Banco BCP' ? 'selected' : '' }}>Banco BCP</option>
                                <option value="Banco Nacional de Bolivia" {{ old('banco') === 'Banco Nacional de Bolivia' ? 'selected' : '' }}>Banco Nacional de Bolivia</option>
                                <option value="Banco Mercantil Santa Cruz" {{ old('banco') === 'Banco Mercantil Santa Cruz' ? 'selected' : '' }}>Banco Mercantil Santa Cruz</option>
                                <option value="Banco BISA" {{ old('banco') === 'Banco BISA' ? 'selected' : '' }}>Banco BISA</option>
                                <option value="Banco Económico" {{ old('banco') === 'Banco Económico' ? 'selected' : '' }}>Banco Económico</option>
                                <option value="Banco FIE" {{ old('banco') === 'Banco FIE' ? 'selected' : '' }}>Banco FIE</option>
                                <option value="Banco Ganadero" {{ old('banco') === 'Banco Ganadero' ? 'selected' : '' }}>Banco Ganadero</option>
                                <option value="Banco Fortaleza" {{ old('banco') === 'Banco Fortaleza' ? 'selected' : '' }}>Banco Fortaleza</option>
                                <option value="Banco ProCredit" {{ old('banco') === 'Banco ProCredit' ? 'selected' : '' }}>Banco ProCredit</option>
                                <option value="Banco Sol" {{ old('banco') === 'Banco Sol' ? 'selected' : '' }}>Banco Sol</option>
                            </optgroup>
                            <optgroup label="Billeteras digitales">
                                <option value="Tigo Money" {{ old('banco') === 'Tigo Money' ? 'selected' : '' }}>Tigo Money</option>
                                <option value="Simple" {{ old('banco') === 'Simple' ? 'selected' : '' }}>Simple</option>
                            </optgroup>
                            <option value="Otro" {{ old('banco') === 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('banco')
                            <div class="rv-error-msg visible">{{ $message }}</div>
                        @enderror
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- ── Resumen del pedido ── --}}
    <div>
        <div class="rv-resumen-card">
            <div class="rv-resumen-head">
                <div class="rv-card-icon"><img src="/images/icono-envio.png" alt=""></div>
                Resumen del pedido
            </div>
            <div class="rv-resumen-body">
                @foreach($items as $item)
                <div class="rv-resumen-row">
                    <span class="label">
                        {{ $item['nombre'] }}
                        <span class="qty">× {{ $item['cantidad'] }}</span>
                    </span>
                    <span class="value">Bs. {{ number_format($item['subtotal'], 2) }}</span>
                </div>
                @endforeach
            </div>
            <div class="rv-resumen-total">
                <span class="label">Total a pagar</span>
                <span class="value">Bs. {{ number_format($total, 2) }}</span>
            </div>
            <div class="rv-resumen-foot">
                <button type="submit" form="formCheckout" class="btn btn-dark">
                    <img src="/images/icono-agregar.png" alt="" style="filter:brightness(0) invert(1);">
                    Continuar al pago QR
                </button>
                <a href="{{ route('cliente.carrito') }}" class="btn btn-ghost">
                    <img src="/images/icono-regresar.png" alt="">
                    Volver al carrito
                </a>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
// Mostrar alerta si el nombre parece incompleto
function validarTitular(input) {
    const val   = input.value.trim();
    const alerta = document.getElementById('alertaTitular');
    // Menos de 2 palabras o menos de 6 caracteres → mostrar advertencia
    const palabras = val.split(/\s+/).filter(p => p.length > 0);
    const incompleto = palabras.length < 2 || val.length < 6;
    alerta.classList.toggle('visible', val.length > 0 && incompleto);
}
</script>
@endpush

@endsection