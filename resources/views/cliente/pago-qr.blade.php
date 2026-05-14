@extends('layout')
@section('title', 'Pagar con QR — Rayo Verde')

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
.rv-steps { display: flex; align-items: center; gap: 0; margin-bottom: 28px; }
.rv-step { display: flex; align-items: center; gap: 10px; font-size: 12px; font-weight: 600; color: var(--ink-muted); }
.rv-step-num {
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--border); color: var(--ink-muted);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; flex-shrink: 0;
}
.rv-step.active .rv-step-num { background: var(--green-600); color: #fff; }
.rv-step.active { color: var(--ink); }
.rv-step.done .rv-step-num { background: var(--green-100); color: var(--green-600); }
.rv-step-line { flex: 1; height: 2px; background: var(--border); margin: 0 12px; max-width: 60px; }
.rv-step.done + .rv-step-line { background: var(--green-400); }

/* Layout centrado */
.rv-qr-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 24px; align-items: start; max-width: 900px; margin: 0 auto;
}
@media (max-width: 820px) { .rv-qr-layout { grid-template-columns: 1fr; max-width: 480px; } }

/* Card QR */
.rv-qr-card {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius-md); overflow: hidden;
    box-shadow: var(--shadow-card);
}
.rv-qr-head {
    padding: 16px 24px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 10px;
    font-size: 14px; font-weight: 600; color: var(--ink); background: var(--white);
}
.rv-card-icon {
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    background: var(--green-100); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.rv-card-icon img { width: 18px; height: 18px; object-fit: contain; }

/* Contenedor QR */
.rv-qr-body { padding: 28px 24px; display: flex; flex-direction: column; align-items: center; gap: 20px; }

.rv-qr-img-wrap {
    position: relative;
    width: 240px; height: 240px;
    border-radius: var(--radius-md);
    border: 2px solid var(--border);
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(26,61,6,0.12);
}
.rv-qr-img-wrap img {
    width: 100%; height: 100%; object-fit: contain;
}
/* Marco decorativo verde */
.rv-qr-img-wrap::before {
    content: '';
    position: absolute; inset: 0;
    border: 4px solid transparent;
    border-radius: var(--radius-md);
    background: linear-gradient(var(--white), var(--white)) padding-box,
                linear-gradient(135deg, var(--green-500), var(--green-400)) border-box;
    pointer-events: none;
}

/* Monto destacado */
.rv-monto {
    text-align: center;
}
.rv-monto-lbl { font-size: 11px; font-weight: 600; color: var(--ink-muted); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 4px; }
.rv-monto-val {
    font-family: 'Instrument Serif', serif; font-size: 36px;
    color: var(--green-700); letter-spacing: -1px; line-height: 1;
}

/* Código de referencia */
.rv-codigo-wrap {
    width: 100%; background: var(--green-50);
    border: 1px solid var(--border); border-radius: var(--radius-sm);
    padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; gap: 10px;
}
.rv-codigo-lbl { font-size: 10px; font-weight: 600; color: var(--ink-muted); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 2px; }
.rv-codigo-val { font-size: 14px; font-weight: 700; color: var(--green-700); font-variant-numeric: tabular-nums; letter-spacing: 1px; }
.rv-codigo-copy {
    background: var(--green-100); border: 1px solid var(--green-400);
    border-radius: 6px; padding: 6px 12px;
    font-family: 'Sora', sans-serif; font-size: 11px; font-weight: 600;
    color: var(--green-700); cursor: pointer; transition: all .15s; flex-shrink: 0;
    white-space: nowrap;
}
.rv-codigo-copy:hover { background: var(--green-400); color: var(--white); }
.rv-codigo-copy.copiado { background: var(--green-600); color: #fff; border-color: var(--green-600); }

/* Info del pagador */
.rv-pagador-info {
    width: 100%; background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 12px 16px;
    display: flex; flex-direction: column; gap: 6px;
}
.rv-pagador-row { display: flex; align-items: center; gap: 8px; font-size: 12px; }
.rv-pagador-row .k { color: var(--ink-muted); font-weight: 500; min-width: 50px; }
.rv-pagador-row .v { color: var(--ink); font-weight: 600; }

/* Instrucciones */
.rv-instrucciones {
    width: 100%; background: var(--amber-100); border: 1px solid #f0d9b0;
    border-radius: var(--radius-sm); padding: 14px 16px;
    font-size: 12px; color: var(--amber-700); line-height: 1.7;
}
.rv-instrucciones strong { display: block; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 6px; }
.rv-instrucciones ol { padding-left: 16px; }
.rv-instrucciones li { margin-bottom: 4px; }

/* Panel lateral de acciones */
.rv-acciones-card {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius-md); overflow: hidden;
    box-shadow: var(--shadow-card); position: sticky; top: 20px;
}
.rv-acciones-head {
    padding: 16px 20px; border-bottom: 1px solid var(--border);
    font-size: 14px; font-weight: 600; color: var(--ink); background: var(--white);
    display: flex; align-items: center; gap: 10px;
}
.rv-acciones-body { padding: 20px; display: flex; flex-direction: column; gap: 12px; }

/* Estado de espera animado */
.rv-waiting {
    text-align: center; padding: 16px 0 8px;
}
.rv-waiting-dots { display: flex; justify-content: center; gap: 6px; margin-bottom: 10px; }
.rv-waiting-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--green-400);
    animation: dotsAnim 1.4s ease-in-out infinite;
}
.rv-waiting-dot:nth-child(2) { animation-delay: .2s; }
.rv-waiting-dot:nth-child(3) { animation-delay: .4s; }
@keyframes dotsAnim { 0%,100% { opacity:.25; transform:scale(.85); } 50% { opacity:1; transform:scale(1.1); } }
.rv-waiting-txt { font-size: 12px; color: var(--ink-muted); line-height: 1.5; }

/* Divisor */
.rv-divider { height: 1px; background: var(--border); margin: 4px 0; }

/* Botones */
.btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    padding: 11px 20px; border-radius: var(--radius-sm);
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