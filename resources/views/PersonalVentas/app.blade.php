<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Rayo Verde Ventas</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        verde: {
                            50:  '#f0faf4', 100: '#d9f2e4', 200: '#b3e5c9',
                            300: '#7dcba8', 400: '#46aa81', 500: '#27ae60',
                            600: '#1e8f4e', 700: '#18733e', 800: '#145c32', 900: '#0f4225',
                        },
                    },
                    fontFamily: {
                        sans:    ['DM Sans', 'sans-serif'],
                        display: ['DM Serif Display', 'serif'],
                    },
                }
            }
        }
    </script>


    <link rel="stylesheet" href="{{ asset('/css/sidebarAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('/css/estilosDash.css') }}">

    @stack('styles')
</head>

<body>

{{-- ── OVERLAY MOBILE ── --}}
<div id="sidebar-overlay"
     class="fixed inset-0 z-30 hidden opacity-0 lg:hidden"
     onclick="toggleSidebar()"></div>

{{-- ══════════════════════════════════════════
     SIDEBAR OSCURA FIJA (MÓDULO VENTAS)
══════════════════════════════════════════ --}}
<aside id="sidebar" class="fixed top-0 left-0 h-screen z-40 flex flex-col sidebar-hidden lg:translate-x-0">

    {{-- LOGO CORPORATIVO --}}
    <div class="sidebar-brand flex items-center gap-2.5 px-4 py-4">
        <div class="w-8 h-8 rounded-lg overflow-hidden flex items-center justify-center flex-shrink-0"
             style="background:linear-gradient(135deg,#27ae60,#2ecc71);">
            <img src="{{ asset('images/logo.png') }}" alt="Rayo Verde" class="w-full h-full object-cover">
        </div>
        <div>
            <p class="font-display text-white tracking-widest" style="font-size:0.9rem; line-height:1.1;">RAYO VERDE</p>
            <p style="font-size:0.57rem; color:#3a6a4a; letter-spacing:0.12em; text-transform:uppercase; font-weight:600;">Módulo Ventas</p>
        </div>
        <button onclick="toggleSidebar()" class="ml-auto lg:hidden" style="color:#58744f;">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>

    {{-- NAVEGACIÓN --}}
    <nav id="sidebar-nav" class="flex-1 overflow-y-auto px-2.5 pb-4">

        <p class="slabel">Principal</p>
        <a href="{{ route('ventas.dashboard') }}" class="nav-item">
            <i class="fas fa-th-large ni"></i><span>Panel General</span>
        </a>

        <p class="slabel">Atención Digital</p>
        <a href="{{ route('ventas.chat.bandeja') }}" class="nav-item">
            <i class="fas fa-comments ni"></i><span>Bandeja de Chats</span>
        </a>

        <p class="slabel">Operaciones</p>
        <a href="{{ route('ventas.cotizaciones.index') }}" class="nav-item">
            <i class="fas fa-file-invoice-dollar ni"></i><span>Cotizaciones</span>
        </a>

    </nav>

    {{-- PERFIL BOTTOM --}}
    <div class="flex items-center gap-2.5 px-3 py-3"
         style="border-top:1px solid rgba(255,255,255,0.05); background:#0D1F03;">
        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
             style="background:linear-gradient(135deg,#1b4d2b,#4CAF50);">
            <i class="fas fa-user text-white" style="font-size:0.65rem;"></i>
        </div>
        <div class="min-w-0 flex-1">
            <p style="font-size:0.77rem; font-weight:600; color:#c8e6c8;">{{ Auth::user()->nombre }}</p>
            <p style="font-size:0.63rem; color:#58744f;" class="truncate">Personal de Ventas</p>
        </div>
        
        <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
            @csrf
        </form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Cerrar sesión"
           style="color:#58744f;" class="flex-shrink-0 transition-colors"
           onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#58744f'">
            <i class="fas fa-sign-out-alt text-sm"></i>
        </a>
    </div>

</aside>


{{-- ══════════════════════════════════════════
     WRAPPER PRINCIPAL
══════════════════════════════════════════ --}}
<div class="lg:ml-[232px] min-h-screen flex flex-col">

    {{-- HEADER --}}
    <header class="admin-header sticky top-0 z-20">
        <div class="top-stripe"></div>
        <div class="flex items-center justify-between px-4 sm:px-6 h-[54px]">

            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()"
                        class="lg:hidden w-8 h-8 rounded-lg flex items-center justify-center"
                        style="background:#f5fbf2; color:#4CAF50; border:1px solid #cfe6cb;">
                    <i class="fas fa-bars" style="font-size:0.8rem;"></i>
                </button>
                <div class="hidden sm:block w-px h-5" style="background:#d8e8d3;"></div>
                <nav class="hidden sm:flex items-center gap-1.5">
                    <a href="{{ route('ventas.dashboard') }}" class="flex items-center gap-1.5 font-semibold" style="font-size:0.78rem; color:#2d7a2d;">
                        <i class="fas fa-home" style="font-size:0.7rem;"></i>
                        <span>Ventas</span>
                    </a>
                    @hasSection('breadcrumb')
                        <i class="fas fa-chevron-right" style="font-size:0.5rem; color:#a8d5a2;"></i>
                        <span style="font-size:0.78rem; color:#4a6e4a; font-weight:500;">@yield('breadcrumb')</span>
                    @endif
                </nav>
            </div>

            <div class="flex items-center gap-2">
                {{-- Usuario en Header --}}
                <div class="u-pill flex items-center gap-2 px-3 py-1 rounded-full" style="background:#f5fbf2; border:1px solid #cfe6cb;">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center" style="background:#27ae60;">
                        <i class="fas fa-user text-white" style="font-size:0.6rem;"></i>
                    </div>
                    <span style="font-size:0.73rem; font-weight:700; color:#1b4d2b;">{{ Auth::user()->nombre }}</span>
                </div>
            </div>

        </div>
    </header>

    {{-- CONTENIDO DINÁMICO --}}
    <main class="flex-1 p-4 sm:p-6 lg:p-8">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="admin-footer">
        <div class="px-6 py-3 flex items-center justify-between">
            <p style="font-size:0.73rem; color:#58744f;">
                &copy; {{ date('Y') }} <span style="color:#7BE07B; font-weight:600;">Rayo Verde</span> · Todos los derechos reservados
            </p>
        </div>
    </footer>

</div>

{{-- SCRIPTS DE COMPORTAMIENTO --}}
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
                }
            } catch(_) {}
        });
    });
</script>

@stack('scripts')
</body>
</html>