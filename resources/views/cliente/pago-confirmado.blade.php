@extends('layout')
@section('title', 'Pago Confirmado — Rayo Verde')

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

/* Pasos — todos done */
.rv-steps { display: flex; align-items: center; gap: 0; margin-bottom: 32px; }
.rv-step { display: flex; align-items: center; gap: 10px; font-size: 12px; font-weight: 600; color: var(--ink-muted); }
.rv-step-num {
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--border); color: var(--ink-muted);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; flex-shrink: 0;
}
.rv-step.done .rv-step-num { background: var(--green-100); color: var(--green-600); }
.rv-step.done { color: var(--green-700); }
.rv-step.active .rv-step-num { background: var(--green-600); color: #fff; }
.rv-step.active { color: var(--ink); }
.rv-step-line { flex: 1; height: 2px; background: var(--green-400); margin: 0 12px; max-width: 60px; }

/* Layout centrado */
.rv-confirmado-wrap {
    max-width: 600px; margin: 0 auto;
    display: flex; flex-direction: column; gap: 16px;
}

/* Check animado */
.rv-check-circle {
    width: 80px; height: 80px; border-radius: 50%;
    background: var(--green-100);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 8px;
    animation: popIn .5s cubic-bezier(.34,1.5,.64,1) both;
}
@keyframes popIn {
    from { transform: scale(0); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
.rv-check-circle svg { width: 38px; height: 38px; }

/* Card principal */
.rv-card {
    background: var(--white); border: 1px solid var(--border);
    border-radius: var(--radius-md); overflow: hidden;
    box-shadow: var(--shadow-card);
}
.rv-card-head {
    padding: 20px 24px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 10px;
    font-size: 14px; font-weight: 600; color: var(--ink); background: var(--white);
}
.rv-card-icon {
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    background: var(--green-100); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.rv-card-icon img { width: 18px; height: 18px; object-fit: contain; }
.rv-card-icon-amber { background: var(--amber-100); }
.rv-card-body { padding: 24px; }

/* Mensaje principal */
.rv-confirmado-msg {
    text-align: center; padding: 32px 24px 24px;
}
.rv-confirmado-titulo {
    font-family: 'Instrument Serif', serif; font-size: 28px;
    color: var(--ink); font-weight: 400; letter-spacing: -0.5px; margin-bottom: 10px;
}
.rv-confirmado-titulo em { font-style: italic; color: var(--green-600); }
.rv-confirmado-sub {
    font-size: 14px; color: var(--ink-mid); line-height: 1.7; max-width: 420px; margin: 0 auto;
}

/* Código destacado */
.rv-codigo-box {
    background: var(--green-50); border: 1.5px solid var(--border);
    border-radius: var(--radius-sm); padding: 14px 20px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; margin: 20px 24px 0;
}
.rv-codigo-lbl { font-size: 10px; font-weight: 700; color: var(--ink-muted); letter-spacing: 1.2px; text-transform: uppercase; margin-bottom: 3px; }
.rv-codigo-val { font-size: 18px; font-weight: 800; color: var(--green-700); letter-spacing: 1.5px; font-variant-numeric: tabular-nums; }
.rv-codigo-copy {
    background: var(--green-100); border: 1px solid var(--green-400);
    border-radius: 6px; padding: 6px 14px;
    font-family: 'Sora', sans-serif; font-size: 11px; font-weight: 600;
    color: var(--green-700); cursor: pointer; transition: all .15s; flex-shrink: 0;
}
.rv-codigo-copy:hover { background: var(--green-400); color: #fff; }
.rv-codigo-copy.copiado { background: var(--green-600); color: #fff; border-color: var(--green-600); }

/* Resumen del pedido */
.rv-resumen-row {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: 9px 0; border-bottom: 1px solid #f0f4eb; font-size: 13px; gap: 8px;
}
.rv-resumen-row:last-child { border-bottom: none; }
.rv-resumen-row .label { color: var(--ink-muted); flex: 1; }
.rv-resumen-row .qty { font-size: 11px; color: var(--ink-muted); margin-left: 4px; }
.rv-resumen-row .value { font-weight: 600; color: var(--ink); font-variant-numeric: tabular-nums; }
.rv-resumen-total {
    display: flex; justify-content: space-between;
    padding: 14px 24px; background: var(--green-50);
    border-top: 2px solid var(--border);
    font-weight: 700; font-size: 14px;
}
.rv-resumen-total .monto {
    font-family: 'Instrument Serif', serif; font-size: 24px;
    color: var(--green-700); letter-spacing: -0.5px;
}

/* Alerta de espera */
.rv-alerta-espera {
    background: var(--amber-100); border: 1px solid #f0d9b0;
    border-radius: var(--radius-sm); padding: 16px 20px;
    display: flex; gap: 12px; align-items: flex-start;
}
.rv-alerta-espera-ico { font-size: 18px; flex-shrink: 0; }
.rv-alerta-espera-txt { font-size: 13px; color: var(--amber-700); line-height: 1.6; }
.rv-alerta-espera-txt strong { display: block; margin-bottom: 4px; font-size: 13px; }

/* Datos del pagador */
.rv-pagador-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
}
.rv-pagador-item { }
.rv-pagador-lbl { font-size: 10px; font-weight: 700; color: var(--ink-muted); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 3px; }
.rv-pagador-val { font-size: 13px; font-weight: 600; color: var(--ink); }

/* Botones */
.rv-acciones { display: flex; flex-direction: column; gap: 10px; padding: 20px 24px; background: var(--surface); border-top: 1px solid var(--border); }
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

/* Dots animados */
.rv-dots { display: flex; justify-content: center; gap: 6px; margin-top: 16px; }
.rv-dot {
    width: 7px; height: 7px; border-radius: 50%; background: var(--green-400);
    animation: dotPulse 1.4s ease-in-out infinite;
}
.rv-dot:nth-child(2) { animation-delay: .2s; }
.rv-dot:nth-child(3) { animation-delay: .4s; }
@keyframes dotPulse { 0%,100% { opacity:.25; transform:scale(.85); } 50% { opacity:1; transform:scale(1.1); } }
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