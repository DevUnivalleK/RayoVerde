<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Rayo Verde Admin</title>

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

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/slidebarAdmin.css') }}">
    @endpush

    @stack('styles')
</head>

<body>

{{-- ── OVERLAY MOBILE ── --}}
<div id="sidebar-overlay"
     class="fixed inset-0 z-30 hidden opacity-0 lg:hidden"
     onclick="toggleSidebar()"></div>

{{-- ══════════════════════════════════════════
     SIDEBAR OSCURA FIJA
══════════════════════════════════════════ --}}
<aside id="sidebar" class="fixed top-0 left-0 h-screen z-40 flex flex-col sidebar-hidden lg:translate-x-0">

    {{-- LOGO --}}
    <div class="sidebar-brand flex items-center gap-2.5 px-4 py-4">
        <div class="w-8 h-8 rounded-lg overflow-hidden flex items-center justify-center flex-shrink-0"
             style="background:linear-gradient(135deg,#27ae60,#2ecc71);">
            <img src="{{ asset('images/logo.png') }}" alt="Rayo Verde"
                 class="w-full h-full object-cover">
        </div>
        <div>
            <p class="font-display text-white tracking-widest" style="font-size:0.9rem; line-height:1.1;">RAYO VERDE</p>
            <p style="font-size:0.57rem; color:#3a6a4a; letter-spacing:0.12em; text-transform:uppercase; font-weight:600;">Admin System</p>
        </div>
        <button onclick="toggleSidebar()" class="ml-auto lg:hidden" style="color:#58744f;">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>

    {{-- NAVEGACIÓN --}}
    <nav id="sidebar-nav" class="flex-1 overflow-y-auto px-2.5 pb-4">

        <p class="slabel">Principal</p>

        <a href="{{ route('admin.dashboard') }}" class="nav-item">
            <i class="fas fa-th-large ni"></i><span>Dashboard</span>
        </a>

        <p class="slabel">Gestión</p>

        {{-- Productos --}}
        <button onclick="toggleSubmenu('sub-prod',this)" class="nav-item">
            <i class="fas fa-box-open ni"></i>
            <span class="flex-1 text-left">Productos</span>
            <i class="fas fa-chevron-right s-arrow"></i>
        </button>
        <div id="sub-prod" class="submenu pl-5 mt-0.5 space-y-0.5">
            <a href="{{ route('admin.productos.index') }}" class="nav-item" style="font-size:0.77rem; padding:6px 10px;">
                <i class="fas fa-list ni" style="font-size:0.68rem;"></i><span>Ver todos</span>
            </a>
            <a href="{{ route('admin.productos.crear') }}" class="nav-item" style="font-size:0.77rem; padding:6px 10px;">
                <i class="fas fa-plus ni" style="font-size:0.68rem;"></i><span>Agregar producto</span>
            </a>
        </div>

        {{-- Envíos/Regiones --}}
        <a href="#" class="nav-item">
            <i class="fas fa-truck ni"></i><span>Envíos / Regiones</span>
        </a>

        {{-- Gestión de Ventas --}}
        <button onclick="toggleSubmenu('sub-ventas',this)" class="nav-item">
            <i class="fas fa-cash-register ni"></i>
            <span class="flex-1 text-left">Gestión de Ventas</span>
            <i class="fas fa-chevron-right s-arrow"></i>
        </button>
        <div id="sub-ventas" class="submenu pl-5 mt-0.5 space-y-0.5">
            <a href="{{ route('admin.ventas.index') }}" class="nav-item" style="font-size:0.77rem; padding:6px 10px;">
                <i class="fas fa-chart-line ni" style="font-size:0.68rem;"></i><span>Ventas</span>
            </a>
            <a href="{{ route('admin.pedidos.bandeja') }}" class="nav-item" style="font-size:0.77rem; padding:6px 10px;">
                <i class="fas fa-shopping-bag ni" style="font-size:0.68rem;"></i>
                <span class="flex-1">Bandeja Pedidos</span>
                <span class="bp rounded-full text-white font-bold flex items-center justify-center"
                      style="background:#4CAF50; font-size:0.55rem; min-width:15px; height:15px; padding:0 3px;">!</span>
            </a>
            <a href="{{ url('/mis-cotizaciones') }}" class="nav-item" style="font-size:0.77rem; padding:6px 10px;">
                <i class="fas fa-file-invoice-dollar ni" style="font-size:0.68rem;"></i><span>Cotizaciones</span>
            </a>
        </div>

        <hr class="sdiv">

        <a href="{{ route('admin.faq.index') }}" class="nav-item">
            <i class="fas fa-question-circle ni"></i><span>FAQ</span>
        </a>

        <p class="slabel">Análisis</p>

        <!-- <a href="{{ route('admin.dashboard') }}" class="nav-item">
            <i class="fas fa-tachometer-alt ni"></i><span>Dashboards</span>
        </a> -->

        {{-- Reportes --}}
<button onclick="toggleSubmenu('sub-rep',this)" class="nav-item">
    <i class="fas fa-chart-bar ni"></i>
    <span class="flex-1 text-left">Reportes</span>
    <i class="fas fa-chevron-right s-arrow"></i>
</button>
<div id="sub-rep" class="submenu pl-5 mt-0.5 space-y-0.5">
    <a href="{{ route('admin.reportes.general') }}" class="nav-item" style="font-size:0.77rem; padding:6px 10px;">
        <i class="fas fa-chart-pie ni" style="font-size:0.68rem;"></i><span>General</span>
    </a>
    <a href="{{ route('admin.reportes.porFecha') }}" class="nav-item" style="font-size:0.77rem; padding:6px 10px;">
        <i class="fas fa-calendar-alt ni" style="font-size:0.68rem;"></i><span>Por fecha</span>
    </a>
    <a href="{{ route('admin.reportes.filtros') }}" class="nav-item" style="font-size:0.77rem; padding:6px 10px;">
        <i class="fas fa-filter ni" style="font-size:0.68rem;"></i><span>Filtrado</span>
    </a>
    <a href="{{ route('admin.reportes.exportar.excel') }}" class="nav-item" style="font-size:0.77rem; padding:6px 10px;">
        <i class="fas fa-file-excel ni" style="font-size:0.68rem; color:#22c55e;"></i><span>Excel</span>
    </a>
    <a href="{{ route('admin.reportes.exportar.pdf') }}" class="nav-item" style="font-size:0.77rem; padding:6px 10px;">
        <i class="fas fa-file-pdf ni" style="font-size:0.68rem; color:#ef4444;"></i><span>PDF</span>
    </a>
</div>

        <p class="slabel">Configuración</p>

        <a href="#" class="nav-item">
            <i class="fas fa-sliders-h ni"></i><span>Config. Comercial</span>
        </a>

        <a href="{{ route('chatbot.index') }}" class="nav-item">
            <i class="fas fa-robot ni"></i><span>ChatBot</span>
        </a>

        <hr class="sdiv">

        <a href="{{ route('home') }}" target="_blank" class="nav-item"
           style="border:1px solid rgba(76,175,80,0.2); margin-top:4px;">
            <i class="fas fa-globe ni" style="color:#4CAF50; opacity:1;"></i>
            <span style="color:#7dcba8;">Volver al Sitio</span>
            <i class="fas fa-external-link-alt ml-auto" style="font-size:0.58rem; color:#58744f;"></i>
        </a>

    </nav>

    {{-- PERFIL BOTTOM --}}
    <div class="flex items-center gap-2.5 px-3 py-3"
         style="border-top:1px solid rgba(255,255,255,0.05); background:#0D1F03;">
        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
             style="background:linear-gradient(135deg,#1b4d2b,#4CAF50);">
            <i class="fas fa-user-tie text-white" style="font-size:0.65rem;"></i>
        </div>
        <div class="min-w-0 flex-1">
            <p style="font-size:0.77rem; font-weight:600; color:#c8e6c8;">{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</p>
            <p style="font-size:0.63rem; color:#58744f;" class="truncate">{{ Auth::user()->correo }}</p>
        </div>
        <a href="{{ url('/login') }}" title="Cerrar sesión"
           style="color:#58744f;" class="flex-shrink-0 transition-colors"
           onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#58744f'">
            <i class="fas fa-sign-out-alt text-sm"></i>
        </a>
    </div>

</aside>
{{-- /sidebar --}}


{{-- ══════════════════════════════════════════
     WRAPPER PRINCIPAL
══════════════════════════════════════════ --}}
<div class="lg:ml-[232px] min-h-screen flex flex-col">

    {{-- ═══════════════════════════
         HEADER
    ═══════════════════════════ --}}
    <header class="admin-header sticky top-0 z-20">
        <div class="top-stripe"></div>

        <div class="flex items-center justify-between px-4 sm:px-6 h-[54px]">

            {{-- Izquierda: hamburguesa + breadcrumb --}}
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()"
                        class="lg:hidden w-8 h-8 rounded-lg flex items-center justify-center"
                        style="background:#f5fbf2; color:#4CAF50; border:1px solid #cfe6cb;">
                    <i class="fas fa-bars" style="font-size:0.8rem;"></i>
                </button>

                <div class="hidden sm:block w-px h-5" style="background:#d8e8d3;"></div>

                <nav class="hidden sm:flex items-center gap-1.5">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-1.5 font-semibold"
                       style="font-size:0.78rem; color:#2d7a2d;">
                        <i class="fas fa-home" style="font-size:0.7rem;"></i>
                        <span>Admin</span>
                    </a>
                    @hasSection('breadcrumb')
                        <i class="fas fa-chevron-right" style="font-size:0.5rem; color:#a8d5a2;"></i>
                        <span style="font-size:0.78rem; color:#4a6e4a; font-weight:500;">@yield('breadcrumb')</span>
                    @endif
                </nav>
            </div>

            {{-- Derecha: acciones --}}
            <div class="flex items-center gap-2">

                {{-- Buscador --}}
                <!-- <div class="search-box hidden md:flex">
                    <i class="fas fa-search" style="color:#7dcba8; font-size:0.7rem;"></i>
                    <input type="text" placeholder="Buscar en el panel...">
                </div> -->

                {{-- ── BOTÓN BANDEJA DE PEDIDOS ── --}}
                <a href="{{ route('admin.pedidos.bandeja') }}"
                   class="header-icon-btn"
                   title="Bandeja de Pedidos">
                    <img src="{{ asset('images/inbox.png') }}" alt="Bandeja">
                    {{-- Badge de pedidos pendientes --}}
                    <span class="bp absolute -top-1 -right-1 w-[15px] h-[15px] rounded-full
                                 flex items-center justify-center text-white font-bold"
                          style="background:#ef4444; font-size:0.52rem;">!</span>
                </a>

                {{-- ── BOTÓN NOTIFICACIONES ── --}}
                <div class="relative" id="notif-wrapper">
                    <button onclick="toggleNotifPanel()"
                            class="header-icon-btn"
                            title="Notificaciones">
                        <i class="fas fa-bell"></i>
                        <span class="bp absolute -top-1 -right-1 w-[15px] h-[15px] rounded-full
                                     flex items-center justify-center text-white font-bold"
                              style="background:#ef4444; font-size:0.52rem;">3</span>
                    </button>

                    {{-- Panel notificaciones --}}
                    <div id="notif-panel"
                         class="hidden dropdown-panel absolute right-0 top-11 w-80 z-50 overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-2.5"
                             style="background:#0D1F03; border-bottom:1px solid rgba(76,175,80,0.15);">
                            <span class="font-semibold" style="font-size:0.82rem; color:#d4edda;">
                                <i class="fas fa-bell mr-2" style="color:#4CAF50;"></i>Notificaciones
                            </span>
                            <button onclick="toggleNotifPanel()" style="color:#58744f; font-size:0.75rem;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div style="max-height:240px; overflow-y:auto;">
                            <div class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer"
                                 style="border-bottom:1px solid #f0faf4;">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                                     style="background:#d9f2e4;">
                                    <i class="fas fa-shopping-bag" style="color:#27ae60; font-size:0.65rem;"></i>
                                </div>
                                <div>
                                    <p style="font-size:0.82rem; font-weight:600; color:#2d3436;">Nuevo pedido #1042</p>
                                    <p style="font-size:0.73rem; color:#636e72; margin-top:2px;">Cliente: María López · Bs 156.00</p>
                                    <p style="font-size:0.68rem; color:#b2bec3; margin-top:3px;">Hace 5 min</p>
                                </div>
                            </div>
                            <div class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer"
                                 style="border-bottom:1px solid #f0faf4;">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                                     style="background:#fef9c3;">
                                    <i class="fas fa-exclamation-triangle" style="color:#ca8a04; font-size:0.65rem;"></i>
                                </div>
                                <div>
                                    <p style="font-size:0.82rem; font-weight:600; color:#2d3436;">Stock bajo: Aceite de Oliva</p>
                                    <p style="font-size:0.73rem; color:#636e72; margin-top:2px;">Quedan 3 unidades disponibles</p>
                                    <p style="font-size:0.68rem; color:#b2bec3; margin-top:3px;">Hace 1 hora</p>
                                </div>
                            </div>
                            <div class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                                     style="background:#dbeafe;">
                                    <i class="fas fa-file-alt" style="color:#3b82f6; font-size:0.65rem;"></i>
                                </div>
                                <div>
                                    <p style="font-size:0.82rem; font-weight:600; color:#2d3436;">Nueva cotización</p>
                                    <p style="font-size:0.73rem; color:#636e72; margin-top:2px;">Distribuidora Norte SRL</p>
                                    <p style="font-size:0.68rem; color:#b2bec3; margin-top:3px;">Hace 3 horas</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-center py-2"
                             style="background:#f5fbf2; border-top:1px solid #cfe6cb;">
                            <a href="#" style="font-size:0.75rem; font-weight:600; color:#2d7a2d;">
                                Ver todas las notificaciones
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ── USUARIO ── --}}
                <div class="relative" id="user-wrapper">
                    <button onclick="toggleUserPanel()" class="u-pill">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0"
                             style="background:linear-gradient(135deg,#2d7a2d,#4CAF50);">
                            <i class="fas fa-user text-white" style="font-size:0.62rem;"></i>
                        </div>
                        <div class="hidden sm:block text-left">
                            <p style="font-size:0.73rem; font-weight:700; color:#1b4d2b; line-height:1.2;">{{ Auth::user()->nombre }}</p>
                            <p style="font-size:0.62rem; color:#4CAF50; line-height:1.2;">Rayo Verde</p>
                        </div>
                        <i class="fas fa-chevron-down hidden sm:block" style="font-size:0.55rem; color:#4CAF50;"></i>
                    </button>

                    <div id="user-panel"
                         class="hidden dropdown-panel absolute right-0 top-11 w-52 z-50 overflow-hidden py-1">
                        <div class="px-4 py-2.5" style="background:#f5fbf2; border-bottom:1px solid #cfe6cb;">
                            <p style="font-size:0.82rem; font-weight:700; color:#1b4d2b;">{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</p>
                            <p style="font-size:0.68rem; color:#4CAF50;" class="truncate">{{ Auth::user()->correo }}</p>
                        </div>
                        <a href="{{ route('admin.pedidos.bandeja') }}"
                           class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors"
                           style="font-size:0.82rem; color:#3a5a3a;">
                            <img src="{{ asset('images/inbox.png') }}" alt="" style="width:14px; height:14px; object-fit:contain; opacity:0.7;">
                            Bandeja de Pedidos
                        </a>
                        <a href="{{ route('admin.reportes.index') }}"
                           class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors"
                           style="font-size:0.82rem; color:#3a5a3a;">
                            <i class="fas fa-chart-bar w-4 text-center" style="color:#4CAF50;"></i>Reportes
                        </a>
                        <a href="{{ route('admin.faq.index') }}"
                           class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors"
                           style="font-size:0.82rem; color:#3a5a3a;">
                            <i class="fas fa-question-circle w-4 text-center" style="color:#4CAF50;"></i>FAQ
                        </a>
                        <a href="{{ route('home') }}" target="_blank"
                           class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors"
                           style="font-size:0.82rem; color:#3a5a3a;">
                            <i class="fas fa-globe w-4 text-center" style="color:#4CAF50;"></i>Ver sitio
                        </a>
                        <div style="border-top:1px solid #eef4ea; padding-top:3px; margin-top:3px;">
                            <a href="{{ url('/login') }}"
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

    {{-- ═══════════════════════════
         CONTENIDO
    ═══════════════════════════ --}}
    <main class="flex-1 p-4 sm:p-6 lg:p-8">
        @yield('content')
    </main>

    {{-- ═══════════════════════════
         FOOTER — limpio
    ═══════════════════════════ --}}
    <footer class="admin-footer">
        <div class="px-6 py-3 flex items-center justify-between">

            {{-- Izquierda: copyright --}}
            <p style="font-size:0.73rem; color:#58744f;">
                &copy; {{ date('Y') }} <span style="color:#7BE07B; font-weight:600;">Rayo Verde</span>
                · Todos los derechos reservados
            </p>

            {{-- Derecha: Ver sitio --}}
            <a href="{{ route('home') }}" target="_blank"
               style="font-size:0.72rem; font-weight:600; color:#4CAF50;
                      display:flex; align-items:center; gap:5px; transition:color 0.2s;"
               onmouseover="this.style.color='#7BE07B'" onmouseout="this.style.color='#4CAF50'">
                <i class="fas fa-globe" style="font-size:0.65rem;"></i>
                Ver sitio
                <i class="fas fa-external-link-alt" style="font-size:0.58rem;"></i>
            </a>

        </div>
    </footer>

</div>{{-- /wrapper --}}


{{-- ══════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════ --}}
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

    /* ── Marcar link activo por URL y abrir su submenú ── */
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