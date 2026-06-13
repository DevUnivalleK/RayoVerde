<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Cuenta') | Rayo Verde</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans:    ['DM Sans', 'sans-serif'],
                        display: ['DM Serif Display', 'serif'],
                    },
                }
            }
        }
    </script>

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/sidebarUser.css') }}">
    @endpush

    @stack('styles')
</head>

<body>

{{-- ── OVERLAY MOBILE ── --}}
<div id="sidebar-overlay"
     class="fixed inset-0 bg-black/45 z-30 hidden opacity-0 lg:hidden"
     onclick="toggleSidebar()"></div>

{{-- ══════════════════════════════════════════
     SIDEBAR USUARIO — VERDE BOSQUE OSCURO
══════════════════════════════════════════ --}}
<aside id="sidebar"
       class="fixed top-0 left-0 h-screen z-40 flex flex-col sidebar-hidden lg:translate-x-0">

    {{-- LOGO --}}
    <div class="sidebar-brand flex items-center gap-2.5 px-4 py-[14px]">
        <div class="w-8 h-8 rounded-lg overflow-hidden flex items-center justify-center flex-shrink-0"
             style="background:linear-gradient(135deg,#27ae60,#4CAF50);
                    box-shadow:0 3px 10px rgba(76,175,80,0.35);">
            <img src="{{ asset('images/logo.png') }}" alt="Rayo Verde"
                 class="w-full h-full object-cover">
        </div>
        <div>
            <p class="font-display text-white tracking-widest"
               style="font-size:0.9rem; line-height:1.1;">RAYO VERDE</p>
            <span class="brand-badge">Mi cuenta</span>
        </div>
        <button onclick="toggleSidebar()" class="ml-auto lg:hidden" style="color:#3d6040;">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>

    {{-- NAVEGACIÓN --}}
    <nav id="sidebar-nav" class="flex-1 overflow-y-auto px-2.5 py-3 space-y-0.5">

        <p class="slabel">Principal</p>

        <a href="{{ route('home') }}" class="nav-item">
            <i class="fas fa-home ni"></i><span>Inicio</span>
        </a>

        <a href="{{ route('cliente.catalogo') }}" class="nav-item">
            <i class="fas fa-leaf ni"></i><span>Catálogo de Aceites</span>
        </a>

        <p class="slabel">Mis Compras</p>

        {{-- Carrito --}}
        <a href="{{ route('cliente.carrito') }}" class="nav-item">
            <i class="fas fa-shopping-cart ni"></i>
            <span class="flex-1">Mi Carrito</span>
            @php $cartCount = session('carrito') ? count(session('carrito')) : 0; @endphp
            @if($cartCount > 0)
                <span class="cart-badge">{{ $cartCount }}</span>
            @endif
        </a>

        <a href="{{ route('cliente.checkout') }}" class="nav-item">
            <i class="fas fa-credit-card ni"></i><span>Checkout</span>
        </a>

       <!-- {{-- Mis Pedidos --}}
        <button onclick="toggleSubmenu('sub-pedidos',this)" class="nav-item">
            <i class="fas fa-box ni"></i>
            <span class="flex-1 text-left">Mis Pedidos</span>
            <i class="fas fa-chevron-right s-arrow"></i>
        </button>
        <div id="sub-pedidos" class="submenu pl-5 mt-0.5 space-y-0.5">
            <a href="#" class="nav-item" style="font-size:0.77rem; padding:6px 10px;">
                <i class="fas fa-clock ni" style="font-size:0.68rem;"></i><span>Pendientes</span>
            </a>
            <a href="#" class="nav-item" style="font-size:0.77rem; padding:6px 10px;">
                <i class="fas fa-check-circle ni" style="font-size:0.68rem;"></i><span>Completados</span>
            </a>
        </div>-->

        <a href="{{ url('/mis-cotizaciones') }}" class="nav-item">
            <i class="fas fa-file-invoice-dollar ni"></i><span>Mis Cotizaciones</span>
        </a>

        <hr class="sdiv">

        <p class="slabel">Soporte</p>

        <a href="{{ route('chatbot.index') }}" class="nav-item">
            <i class="fas fa-robot ni"></i><span>ChatBot</span>
        </a>

        <!-- <a href="#" class="nav-item">
            <i class="fas fa-question-circle ni"></i><span>Ayuda / FAQ</span>
        </a> -->

        <hr class="sdiv">

        {{-- Volver al sitio --}}
        <a href="{{ route('home') }}" class="nav-item"
           style="border:1px solid rgba(76,175,80,0.2); margin-top:4px;">
            <i class="fas fa-globe ni" style="color:#4CAF50; opacity:1;"></i>
            <span style="color:#7dcba8;">Volver al Sitio</span>
            <i class="fas fa-external-link-alt ml-auto" style="font-size:0.56rem; color:#3d6040;"></i>
        </a>

    </nav>

    {{-- PERFIL BOTTOM --}}
    <div class="sidebar-bottom flex items-center gap-2.5 px-3 py-3">
        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
             style="background:linear-gradient(135deg,#1e6b2e,#4CAF50);">
            <i class="fas fa-user text-white" style="font-size:0.65rem;"></i>
        </div>
        <div class="min-w-0 flex-1">
            <p style="font-size:0.77rem; font-weight:600; color:#c8e6c8;">
                {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}
            </p>
            <p style="font-size:0.63rem; color:#3d6040;" class="truncate">
                {{ Auth::user()->correo }}
            </p>
        </div>
        <a href="#" onclick="document.getElementById('form-logout').submit(); return false;"
           title="Cerrar sesión" style="color:#3d6040;" class="flex-shrink-0 transition-colors"
           onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#3d6040'">
            <i class="fas fa-sign-out-alt text-sm"></i>
        </a>
        <form id="form-logout" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>

</aside>


{{-- ══════════════════════════════════════════
     WRAPPER PRINCIPAL
══════════════════════════════════════════ --}}
<div class="lg:ml-[224px] min-h-screen flex flex-col">

    {{-- ═══════════════════
         HEADER
    ═══════════════════ --}}
    <header class="user-header sticky top-0 z-20">
        <div class="top-stripe"></div>

        <div class="flex items-center justify-between px-4 sm:px-6 h-[54px]">

            {{-- Izquierda --}}
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()"
                        class="lg:hidden w-8 h-8 rounded-lg flex items-center justify-center"
                        style="background:#f5fbf2; color:#27ae60; border:1px solid #c3e6cb;">
                    <i class="fas fa-bars" style="font-size:0.8rem;"></i>
                </button>

                <div class="hidden sm:block w-px h-5" style="background:#d4edda;"></div>

                <nav class="hidden sm:flex items-center gap-1.5">
                    <a href="{{ route('home') }}"
                       class="flex items-center gap-1.5 font-semibold"
                       style="font-size:0.78rem; color:#27ae60;">
                        <i class="fas fa-home" style="font-size:0.7rem;"></i>
                        <span>Inicio</span>
                    </a>
                    @hasSection('breadcrumb')
                        <i class="fas fa-chevron-right" style="font-size:0.5rem; color:#a8d5a2;"></i>
                        <span style="font-size:0.78rem; color:#4a6e4a; font-weight:500;">@yield('breadcrumb')</span>
                    @endif
                </nav>
            </div>

            {{-- Derecha --}}
            <div class="flex items-center gap-2">

                {{-- Buscador --}}
                <!-- <div class="search-box hidden md:flex">
                    <i class="fas fa-search" style="color:#7dcba8; font-size:0.7rem;"></i>
                    <input type="text" placeholder="Buscar aceites...">
                </div> -->

                {{-- Carrito rápido --}}
                <a href="{{ route('cliente.carrito') }}" class="hbtn" title="Mi Carrito">
                    <i class="fas fa-shopping-cart"></i>
                    @if($cartCount > 0)
                        <span class="bp absolute -top-1 -right-1 w-[15px] h-[15px] rounded-full
                                     flex items-center justify-center text-white font-bold"
                              style="background:#27ae60; font-size:0.5rem;">{{ $cartCount }}</span>
                    @endif
                </a>

             {{-- Notificaciones --}}
<div class="relative" id="notif-wrapper">
    <button onclick="toggleNotifPanel()" class="hbtn" title="Mis notificaciones">
        <i class="fas fa-bell"></i>
        @if(isset($conteoSinLeer) && $conteoSinLeer > 0)
            <span class="bp absolute -top-1 -right-1 w-[15px] h-[15px] rounded-full
                         flex items-center justify-center text-white font-bold"
                  style="background:#ef4444; font-size:0.5rem;">
                {{ $conteoSinLeer }}
            </span>
        @endif
    </button>

    <div id="notif-panel"
         class="hidden dropdown-panel absolute right-0 top-11 w-80 z-50 overflow-hidden">
        {{-- Cabecera panel --}}
        <div class="flex items-center justify-between px-4 py-2.5"
             style="background:linear-gradient(135deg,#0e2a10,#1e6b2e);
                    border-bottom:1px solid rgba(76,175,80,0.2);">
            <span class="font-semibold" style="font-size:0.82rem; color:#d4edda;">
                <i class="fas fa-bell mr-2" style="color:#4CAF50;"></i>Mis Notificaciones
            </span>
            <button onclick="toggleNotifPanel()"
                    style="color:rgba(255,255,255,0.5); font-size:0.75rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div style="max-height:280px; overflow-y:auto;">
            @if(isset($notificaciones) && $notificaciones->count() > 0)
                @foreach($notificaciones as $noti)
                    @if(!$noti->leida && $noti->tipo === 'APROBADA')
                        {{-- Caso especial: Notificación interactiva para crear el pedido Just in Time --}}
                        <form action="{{ route('notificaciones.leer', $noti->id_notificacion) }}" method="POST" id="form-noti-{{ $noti->id_notificacion }}">
                            @csrf
                            <div onclick="document.getElementById('form-noti-{{ $noti->id_notificacion }}').submit();"
                                 class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer"
                                 style="border-bottom:1px solid #f0faf4; background-color: #f0f9ff;">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                                     style="background:#d9f2e4;">
                                    <i class="fas fa-file-invoice-dollar" style="color:#27ae60; font-size:0.65rem;"></i>
                                </div>
                                <div class="flex-1">
                                    <p style="font-size:0.82rem; font-weight:600; color:#1d2b1a;">¡Cotización Aprobada!</p>
                                    <p style="font-size:0.73rem; color:#3a6040; margin-top:2px; line-height: 1.2;">{{ $noti->mensaje }}</p>
                                    <p style="font-size:0.68rem; color:#7dcba8; margin-top:4px; font-weight: bold; color: #1e6b2e;"><i class="fas fa-shopping-cart mr-1"></i>Haz clic aquí para confirmar y pagar</p>
                                    <p style="font-size:0.65rem; color:#9cbda6; margin-top:2px;">{{ \Carbon\Carbon::parse($noti->enviada_en)->diffForHumans() }}</p>
                                </div>
                            </div>
                        </form>
                    @else
                        {{-- Notificaciones ordinarias (Rechazadas o ya leídas) --}}
                        <div class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition-colors"
                             style="border-bottom:1px solid #f0faf4; background-color: {{ $noti->leida ? '#ffffff' : '#fcfdfb' }};">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                                 style="background: {{ $noti->tipo === 'RECHAZADA' ? '#fde8e8' : '#e8f5e0' }};">
                                <i class="{{ $noti->tipo === 'RECHAZADA' ? 'fas fa-times-circle' : 'fas fa-check-circle' }}" 
                                   style="color: {{ $noti->tipo === 'RECHAZADA' ? '#e53e3e' : '#27ae60' }}; font-size:0.65rem;"></i>
                            </div>
                            <div>
                                <p style="font-size:0.82rem; font-weight:600; color:#1d2b1a;">
                                    {{ $noti->tipo === 'RECHAZADA' ? 'Cotización Rechazada' : 'Notificación' }}
                                </p>
                                <p style="font-size:0.73rem; color:#5c7a5f; margin-top:2px; line-height: 1.2;">{{ $noti->mensaje }}</p>
                                <p style="font-size:0.65rem; color:#9cbda6; margin-top:3px;">{{ \Carbon\Carbon::parse($noti->enviada_en)->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                {{-- Estado vacío --}}
                <div class="text-center py-8" style="color: #727b73;">
                    <i class="fas fa-bell-slash mb-2 d-block" style="font-size: 1.2rem; color: #cbd5e1;"></i>
                    <p style="font-size:0.78rem;">No tienes alertas pendientes</p>
                </div>
            @endif
        </div>
        
        <div class="text-center py-2"
             style="background:#f5fbf2; border-top:1px solid #c3e6cb;">
            <a href="{{ url('/mis-cotizaciones') }}"
               style="font-size:0.75rem; font-weight:600; color:#1e6b2e;">
                Ver historial de cotizaciones
            </a>
        </div>
    </div>
</div>

                {{-- Usuario --}}
                <div class="relative" id="user-wrapper">
                    <button onclick="toggleUserPanel()" class="u-pill">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0"
                             style="background:linear-gradient(135deg,#27ae60,#4CAF50);">
                            <i class="fas fa-user text-white" style="font-size:0.62rem;"></i>
                        </div>
                        <div class="hidden sm:block text-left">
                            <p style="font-size:0.73rem; font-weight:700; color:#1a5c30; line-height:1.2;">
                               {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}
                            </p>
                            <p style="font-size:0.62rem; color:#4CAF50; line-height:1.2;">Cliente</p>
                        </div>
                        <i class="fas fa-chevron-down hidden sm:block" style="font-size:0.55rem; color:#4CAF50;"></i>
                    </button>

                    <div id="user-panel"
                         class="hidden dropdown-panel absolute right-0 top-11 w-52 z-50 overflow-hidden py-1">
                        <div class="px-4 py-2.5" style="background:#f5fbf2; border-bottom:1px solid #c3e6cb;">
                            <p style="font-size:0.82rem; font-weight:700; color:#1a5c30;">
                                {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}
                            </p>
                            <p style="font-size:0.68rem; color:#4CAF50;" class="truncate">
                                {{ Auth::user()->correo }}
                            </p>
                        </div>
                        <a href="{{ route('cliente.carrito') }}"
                           class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors"
                           style="font-size:0.82rem; color:#3a6040;">
                            <i class="fas fa-shopping-cart w-4 text-center" style="color:#27ae60;"></i>Mi Carrito
                        </a>
                        <a href="{{ url('/mis-cotizaciones') }}"
                           class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors"
                           style="font-size:0.82rem; color:#3a6040;">
                            <i class="fas fa-file-invoice-dollar w-4 text-center" style="color:#27ae60;"></i>Mis Cotizaciones
                        </a>
                        <a href="{{ route('catalogo.index') }}"
                           class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors"
                           style="font-size:0.82rem; color:#3a6040;">
                            <i class="fas fa-leaf w-4 text-center" style="color:#27ae60;"></i>Catálogo
                        </a>
                        <a href="{{ route('chatbot.index') }}"
                           class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors"
                           style="font-size:0.82rem; color:#3a6040;">
                            <i class="fas fa-robot w-4 text-center" style="color:#27ae60;"></i>ChatBot
                        </a>
                        <div style="border-top:1px solid #eef4ea; padding-top:3px; margin-top:3px;">
                            <a href="#" onclick="document.getElementById('form-logout').submit(); return false;"
                               class="flex items-center gap-3 px-4 py-2 hover:bg-red-50 transition-colors font-semibold"
                               style="font-size:0.82rem; color:#ef4444;">
                                <i class="fas fa-sign-out-alt w-4 text-center"></i>Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    {{-- ═══════════════════
         CONTENIDO
    ═══════════════════ --}}
    <main class="flex-1 p-4 sm:p-6 lg:p-8">
        @yield('content')
    </main>

    {{-- ═══════════════════
         FOOTER
    ═══════════════════ --}}
    <footer class="user-footer">
        <div class="px-6 py-3 flex items-center justify-between">

            <p style="font-size:0.73rem; color:#3d6040;">
                &copy; {{ date('Y') }}
                <span style="color:#7BE07B; font-weight:600;">Rayo Verde</span>
                · Todos los derechos reservados
            </p>

            <div class="flex items-center gap-4" style="font-size:0.72rem;">
                <a href="{{ route('catalogo.index') }}" style="color:#4CAF50; font-weight:500;"
                   onmouseover="this.style.color='#7BE07B'" onmouseout="this.style.color='#4CAF50'">
                    Catálogo
                </a>
                <span style="color:#1e3a14;">·</span>
                <a href="{{ route('chatbot.index') }}" style="color:#4CAF50; font-weight:500;"
                   onmouseover="this.style.color='#7BE07B'" onmouseout="this.style.color='#4CAF50'">
                    ChatBot
                </a>
                <span style="color:#1e3a14;">·</span>
                <a href="{{ route('home') }}"
                   style="color:#4CAF50; font-weight:600; display:flex; align-items:center; gap:4px;"
                   onmouseover="this.style.color='#7BE07B'" onmouseout="this.style.color='#4CAF50'">
                    <i class="fas fa-globe" style="font-size:0.6rem;"></i>
                    Inicio
                </a>
            </div>

        </div>
    </footer>

</div>

{{-- ══════════════════════════════
     SCRIPTS
══════════════════════════════ --}}
<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    function toggleSidebar() {
        const hidden = sidebar.classList.contains('sidebar-hidden');
        sidebar.classList.toggle('sidebar-hidden', !hidden);
        if (!hidden) {
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 280);
        } else {
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.remove('opacity-0'), 10);
        }
    }

    function toggleNotifPanel() {
        document.getElementById('user-panel').classList.add('hidden');
        document.getElementById('notif-panel').classList.toggle('hidden');
    }

    function toggleUserPanel() {
        document.getElementById('notif-panel').classList.add('hidden');
        document.getElementById('user-panel').classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        if (!document.getElementById('notif-wrapper').contains(e.target))
            document.getElementById('notif-panel').classList.add('hidden');
        if (!document.getElementById('user-wrapper').contains(e.target))
            document.getElementById('user-panel').classList.add('hidden');
    });

    function toggleSubmenu(id, btn) {
        const sub   = document.getElementById(id);
        const arrow = btn.querySelector('.s-arrow');
        sub.classList.toggle('open');
        if (arrow) arrow.style.transform = sub.classList.contains('open') ? 'rotate(90deg)' : '';
    }

    /* Marcar link activo + abrir submenú correspondiente */
    document.addEventListener('DOMContentLoaded', function () {
        const links   = document.querySelectorAll('#sidebar-nav a.nav-item');
        const current = window.location.pathname;
        links.forEach(link => {
            try {
                const href = link.getAttribute('href');
                if (!href || href === '#') return;
                const lp = new URL(href, window.location.origin).pathname;
                if (current === lp || (lp.length > 1 && current.startsWith(lp))) {
                    link.classList.add('active');
                    const sub = link.closest('.submenu');
                    if (sub) {
                        sub.classList.add('open');
                        const arrow = sub.previousElementSibling?.querySelector('.s-arrow');
                        if (arrow) arrow.style.transform = 'rotate(90deg)';
                    }
                }
            } catch(_) {}
        });
    });
</script>

@stack('scripts')
</body>
</html>