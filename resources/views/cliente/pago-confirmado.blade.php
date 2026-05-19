@extends('layout')
@section('title', 'Pago Confirmado — Rayo Verde')

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
        <div class="rv-hero-eyebrow">Pedido</div>
        <h1>¡Pago <em>Notificado</em>!</h1>
        <p>Tu solicitud de pago fue recibida. Pronto la verificaremos.</p>
    </div>
</div>

{{-- Pasos — todos completados --}}
<div class="rv-steps">
    <div class="rv-step done"><span class="rv-step-num">✓</span> Carrito</div>
    <div class="rv-step-line"></div>
    <div class="rv-step done"><span class="rv-step-num">✓</span> Datos de pago</div>
    <div class="rv-step-line"></div>
    <div class="rv-step done"><span class="rv-step-num">✓</span> Pago con QR</div>
    <div class="rv-step-line"></div>
    <div class="rv-step active"><span class="rv-step-num">4</span> Confirmación</div>
</div>

<div class="rv-confirmado-wrap">

    {{-- ── Mensaje principal ── --}}
    <div class="rv-card">
        <div class="rv-confirmado-msg">
            <div class="rv-check-circle">
                <svg viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#edf5e1"/>
                    <path d="M11 19.5L16.5 25L27 14" stroke="#3b6d11" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="rv-confirmado-titulo">¡Listo, <em>gracias</em>!</div>
            <p class="rv-confirmado-sub">
                Hemos recibido tu notificación de pago. Nuestro equipo verificará
                la transacción manualmente y confirmará tu pedido.
                <br><br>
                Este proceso puede demorar <strong>algunas horas</strong>. Te contactaremos cuando sea confirmado.
            </p>
            <div class="rv-dots">
                <div class="rv-dot"></div>
                <div class="rv-dot"></div>
                <div class="rv-dot"></div>
            </div>
        </div>

        {{-- Código de referencia --}}
        <div class="rv-codigo-box">
            <div>
                <div class="rv-codigo-lbl">Tu código de referencia</div>
                <div class="rv-codigo-val" id="codigoRef">{{ $pedido->codigo }}</div>
            </div>
            <button class="rv-codigo-copy" id="btnCopiar" onclick="copiarCodigo()">Copiar</button>
        </div>

        {{-- Resumen del pedido --}}
        <div class="rv-card-head" style="border-top: 1px solid var(--border); border-radius: 0; margin-top: 20px;">
            <div class="rv-card-icon"><img src="/images/icono-envio.png" alt=""></div>
            Resumen del pedido
        </div>
        <div class="rv-card-body">
            @foreach($pedido->carrito as $item)
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
            <span>Total pagado</span>
            <span class="monto">Bs. {{ number_format($pedido->total, 2) }}</span>
        </div>

        {{-- Datos del pagador --}}
        <div class="rv-card-head" style="border-top: 1px solid var(--border); border-radius: 0;">
            <div class="rv-card-icon rv-card-icon-amber"><img src="/images/icono-agregar.png" alt=""></div>
            Datos de la transferencia
        </div>
        <div class="rv-card-body">
            <div class="rv-pagador-grid">
                <div class="rv-pagador-item">
                    <div class="rv-pagador-lbl">Titular de la cuenta</div>
                    <div class="rv-pagador-val">{{ $pedido->nombre_titular }}</div>
                </div>
                <div class="rv-pagador-item">
                    <div class="rv-pagador-lbl">Banco / Billetera</div>
                    <div class="rv-pagador-val">{{ $pedido->banco }}</div>
                </div>
            </div>
        </div>

        {{-- Alerta espera --}}
        <div style="padding: 0 24px 20px;">
            <div class="rv-alerta-espera">
                <span class="rv-alerta-espera-ico">⏳</span>
                <div class="rv-alerta-espera-txt">
                    <strong>¿Qué sigue ahora?</strong>
                    Un administrador de Rayo Verde revisará tu pago en el extracto bancario
                    usando tu código de referencia <strong>{{ $pedido->codigo }}</strong>.
                    Una vez confirmado, tu pedido quedará registrado y nos pondremos en contacto contigo.
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="rv-acciones">
            <a href="{{ route('cliente.catalogo') }}" class="btn btn-dark">
                <img src="/images/icono-region.png" alt="" style="filter:brightness(0) invert(1);">
                Volver al catálogo
            </a>
        </div>
    </div>

</div>

@push('scripts')
<script>
function copiarCodigo() {
    const codigo = document.getElementById('codigoRef').textContent.trim();
    navigator.clipboard.writeText(codigo).then(() => {
        const btn = document.getElementById('btnCopiar');
        btn.textContent = '¡Copiado!';
        btn.classList.add('copiado');
        setTimeout(() => {
            btn.textContent = 'Copiar';
            btn.classList.remove('copiado');
        }, 2500);
    });
}
</script>
@endpush

@endsection