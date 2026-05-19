@extends('layout')
@section('title', 'Pagar con QR — Rayo Verde')

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
        <h1>Pago con <em>QR</em></h1>
        <p>Escanea el código QR con tu app bancaria o billetera digital</p>
    </div>
</div>

{{-- Pasos --}}
<div class="rv-steps">
    <div class="rv-step done"><span class="rv-step-num">✓</span> Carrito</div>
    <div class="rv-step-line"></div>
    <div class="rv-step done"><span class="rv-step-num">✓</span> Datos de pago</div>
    <div class="rv-step-line"></div>
    <div class="rv-step active"><span class="rv-step-num">3</span> Pagar con QR</div>
    <div class="rv-step-line"></div>
    <div class="rv-step"><span class="rv-step-num">4</span> Confirmación</div>
</div>

<div class="rv-qr-layout">

    {{-- ── QR + info ── --}}
    <div class="rv-qr-card">
        <div class="rv-qr-head">
            <div class="rv-card-icon"><img src="/images/icono-agregar.png" alt=""></div>
            Escanea y paga
        </div>
        <div class="rv-qr-body">

            {{-- QR --}}
            <div class="rv-qr-img-wrap">
                <img src="{{ asset('images/qrPago.png') }}" alt="QR de pago Rayo Verde">
            </div>

            {{-- Monto --}}
            <div class="rv-monto">
                <div class="rv-monto-lbl">Monto a pagar</div>
                <div class="rv-monto-val">Bs. {{ number_format($total, 2) }}</div>
            </div>

            {{-- Código de referencia --}}
            <div class="rv-codigo-wrap">
                <div>
                    <div class="rv-codigo-lbl">Código de referencia</div>
                    <div class="rv-codigo-val" id="codigoTexto">{{ $pedido->codigo }}</div>
                </div>
                <button class="rv-codigo-copy" id="btnCopiar" onclick="copiarCodigo()">
                    Copiar
                </button>
            </div>

            {{-- Datos del pagador --}}
            <div class="rv-pagador-info">
                <div class="rv-pagador-row">
                    <span class="k">Titular</span>
                    <span class="v">{{ $pedido->nombre_titular }}</span>
                </div>
                <div class="rv-pagador-row">
                    <span class="k">Banco</span>
                    <span class="v">{{ $pedido->banco }}</span>
                </div>
            </div>

            {{-- Instrucciones --}}
            <div class="rv-instrucciones">
                <strong>¿Cómo pagar?</strong>
                <ol>
                    <li>Abre tu app bancaria o billetera digital.</li>
                    <li>Selecciona la opción <strong>Pagar con QR</strong>.</li>
                    <li>Escanea el código de arriba.</li>
                    <li>Ingresa el monto exacto: <strong>Bs. {{ number_format($total, 2) }}</strong>.</li>
                    <li>Anota el código de referencia <strong>{{ $pedido->codigo }}</strong> en la descripción del pago.</li>
                    <li>Confirma la transacción.</li>
                </ol>
            </div>

        </div>
    </div>

    {{-- ── Acciones ── --}}
    <div class="rv-acciones-card">
        <div class="rv-acciones-head">
            <div class="rv-card-icon"><img src="/images/icono-envio.png" alt=""></div>
            Después de pagar
        </div>
        <div class="rv-acciones-body">

            <div class="rv-waiting">
                <div class="rv-waiting-dots">
                    <div class="rv-waiting-dot"></div>
                    <div class="rv-waiting-dot"></div>
                    <div class="rv-waiting-dot"></div>
                </div>
                <p class="rv-waiting-txt">
                    Una vez que hayas realizado el pago, presiona el botón de abajo para notificarnos.
                    <br><br>
                    Verificaremos tu pago manualmente. Este proceso puede demorar
                    <strong>unas horas</strong>.
                </p>
            </div>

            <div class="rv-divider"></div>

            {{-- Confirmar pago --}}
            <form action="{{ route('cliente.checkout.confirmar') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-dark">
                    <img src="/images/icono-agregar.png" alt="" style="filter:brightness(0) invert(1);">
                    Ya pagué — Confirmar pago
                </button>
            </form>

            <div class="rv-divider"></div>

            <a href="{{ route('cliente.catalogo') }}" class="btn btn-ghost">
                <img src="/images/icono-regresar.png" alt="">
                Volver al catálogo
            </a>

        </div>
    </div>

</div>

@push('scripts')
<script>
function copiarCodigo() {
    const codigo = document.getElementById('codigoTexto').textContent.trim();
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