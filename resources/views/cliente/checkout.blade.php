@extends('layout')
@section('title', 'Checkout — Rayo Verde')

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

/* Hero */
.rv-hero {
    position: relative;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 60%, var(--green-600) 100%);
    border-radius: var(--radius-lg); padding: 32px 36px 36px;
    margin-bottom: 24px; overflow: hidden; box-shadow: var(--shadow-hero);
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
.rv-brand-name { font-family: 'Instrument Serif', serif; font-size: 20px; color: #fff; }
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

/* Pasos */
.rv-steps {
    display: flex; align-items: center; gap: 0;
    margin-bottom: 28px;
}
.rv-step {
    display: flex; align-items: center; gap: 10px;
    font-size: 12px; font-weight: 600; color: var(--ink-muted);
}
.rv-step-num {
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--border); color: var(--ink-muted);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; flex-shrink: 0;
    transition: all .2s;
}
.rv-step.active .rv-step-num { background: var(--green-600); color: #fff; }
.rv-step.active { color: var(--ink); }
.rv-step.done .rv-step-num { background: var(--green-100); color: var(--green-600); }
.rv-step-line { flex: 1; height: 2px; background: var(--border); margin: 0 12px; max-width: 60px; }
.rv-step.done + .rv-step-line { background: var(--green-400); }

/* Layout */
.rv-checkout-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 20px; align-items: start;
}
@media (max-width: 800px) { .rv-checkout-layout { grid-template-columns: 1fr; } }

/* Cards */
.rv-card {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius-md); box-shadow: var(--shadow-card);
    margin-bottom: 16px; overflow: visible;
}
.rv-card:last-child { margin-bottom: 0; }
.rv-card-head {
    padding: 16px 24px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 10px;
    font-size: 14px; font-weight: 600; color: var(--ink);
    border-radius: var(--radius-md) var(--radius-md) 0 0;
    background: var(--white);
}
.rv-card-icon {
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    background: var(--green-100); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.rv-card-icon img { width: 18px; height: 18px; object-fit: contain; }
.rv-card-icon-amber { background: var(--amber-100); }
.rv-card-body { padding: 24px; }

/* Fields */
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
.rv-input.is-error { border-color: var(--error); box-shadow: 0 0 0 3px rgba(192,57,43,0.08); }

.rv-select {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-family: 'Sora', sans-serif; font-size: 13px; color: var(--ink);
    background: var(--white); outline: none; cursor: pointer;
    transition: border-color .2s, box-shadow .2s; appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%237a8f6e' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px;
}
.rv-select:focus { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(79,144,32,0.1); }

.rv-error-msg { font-size: 11px; color: var(--error); margin-top: 5px; display: none; }
.rv-error-msg.visible { display: block; }

/* Alerta nombre titular */
.rv-alerta-titular {
    display: none; margin-top: 8px; padding: 10px 14px;
    background: var(--amber-100); border: 1px solid #f0d9b0;
    border-radius: var(--radius-sm);
    font-size: 12px; color: var(--amber-700); line-height: 1.5;
}
.rv-alerta-titular.visible { display: flex; gap: 8px; align-items: flex-start; }
.rv-alerta-titular .icono { font-size: 14px; flex-shrink: 0; margin-top: 1px; }

/* Flash errores */
.rv-flash-error {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 18px; border-radius: var(--radius-sm);
    font-size: 13px; font-weight: 500; margin-bottom: 16px;
    background: var(--error-bg); color: #7a1f1f; border: 1px solid #f5c2c2;
}

/* Resumen (sticky) */
.rv-resumen-card {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius-md); overflow: hidden;
    box-shadow: var(--shadow-card); position: sticky; top: 20px;
}
.rv-resumen-head {
    padding: 16px 20px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 10px;
    font-size: 14px; font-weight: 600; color: var(--ink); background: var(--white);
}
.rv-resumen-body { padding: 16px 20px; }
.rv-resumen-row {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: 8px 0; border-bottom: 1px solid #f0f4eb; font-size: 13px; gap: 8px;
}
.rv-resumen-row:last-child { border-bottom: none; }
.rv-resumen-row .label { color: var(--ink-muted); flex: 1; }
.rv-resumen-row .qty { color: var(--ink-muted); font-size: 11px; margin-left: 4px; }
.rv-resumen-row .value { font-weight: 600; color: var(--ink); font-variant-numeric: tabular-nums; flex-shrink: 0; }
.rv-resumen-total {
    display: flex; justify-content: space-between; align-items: center;
    padding: 16px 20px; border-top: 2px solid var(--border); background: var(--green-50);
}
.rv-resumen-total .label { font-size: 13px; font-weight: 700; color: var(--ink); }
.rv-resumen-total .value {
    font-family: 'Instrument Serif', serif; font-size: 26px;
    color: var(--green-700); letter-spacing: -0.5px;
}
.rv-resumen-foot { padding: 16px 20px; display: flex; flex-direction: column; gap: 10px; background: var(--surface); }

/* Botones */
.btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    padding: 10px 20px; border-radius: var(--radius-sm);
    font-size: 12px; font-weight: 600; border: none; cursor: pointer;
    text-decoration: none; transition: all .18s ease;
    letter-spacing: 0.2px; font-family: 'Sora', sans-serif; width: 100%;
}
.btn img { width: 14px; height: 14px; object-fit: contain; }
.btn-dark { background: var(--green-600); color: #fff; }
.btn-dark:hover { background: var(--green-700); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(59,109,17,0.3); }
.btn-ghost { background: transparent; color: var(--ink-mid); border: 1.5px solid var(--border); }
.btn-ghost:hover { background: var(--green-100); border-color: var(--green-500); color: var(--green-700); }
</style>
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