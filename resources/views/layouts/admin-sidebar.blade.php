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

    <style>
        /* ══════════════════════════════
           PALETA GENERAL
           Fondo principal: #0D1F03
           Verde secundario: #16360A
           Verde hover: #214D12
           Verde accent: #4CAF50
           Verde claro: #7BE07B
        ══════════════════════════════ */

        /* ── Scrollbar sidebar ── */
        #sidebar-nav::-webkit-scrollbar       { width: 4px; }
        #sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        #sidebar-nav::-webkit-scrollbar-thumb { background: #4CAF50; border-radius: 99px; }

        /* ── Scrollbar global ── */
        ::-webkit-scrollbar       { width: 6px; }
        ::-webkit-scrollbar-track { background: #eef4ea; }
        ::-webkit-scrollbar-thumb { background: #4CAF50; border-radius: 99px; }

        /* ── Base body ── */
        body { background: #eef2eb; font-family: 'DM Sans', sans-serif; color: #1d2b1a; }

        /* ══════════════════════════════
           SIDEBAR
        ══════════════════════════════ */
        #sidebar {
            background: linear-gradient(180deg, #0D1F03 0%, #102706 35%, #16360A 100%);
            width: 232px;
            transition: transform 0.28s cubic-bezier(0.4,0,0.2,1);
            border-right: 1px solid rgba(111,255,111,0.08);
            box-shadow: 6px 0 24px rgba(0,0,0,0.22);
        }
        @media (max-width: 1023px) {
            #sidebar.sidebar-hidden { transform: translateX(-100%); }
        }

        .sidebar-brand {
            border-bottom: 1px solid rgba(255,255,255,0.05);
            background: rgba(255,255,255,0.015);
            backdrop-filter: blur(6px);
        }

        /* ── Nav items ── */
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 12px;
            font-size: 0.84rem; font-weight: 500;
            color: #a7b9a1;
            text-decoration: none; cursor: pointer; width: 100%;
            transition: background 0.18s ease, color 0.18s ease,
                        transform 0.18s ease, box-shadow 0.18s ease;
        }
        .nav-item .ni {
            width: 16px; text-align: center; font-size: 0.76rem;
            opacity: 0.7; flex-shrink: 0; transition: opacity 0.18s ease;
        }
        .nav-item:hover {
            background: rgba(76,175,80,0.12);
            color: #ecffe8;
            transform: translateX(2px);
        }
        .nav-item:hover .ni { opacity: 1; }
        .nav-item.active {
            background: linear-gradient(135deg, #2d7a2d 0%, #4CAF50 55%, #67d467 100%);
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(76,175,80,0.28), inset 0 1px 0 rgba(255,255,255,0.12);
        }
        .nav-item.active .ni { opacity: 1; color: #ffffff; }

        /* ── Section labels ── */
        .slabel {
            font-size: 0.58rem; letter-spacing: 0.15em; text-transform: uppercase;
            color: #58744f; font-weight: 700; padding: 14px 11px 5px;
        }

        /* ── Submenu ── */
        .submenu { overflow: hidden; max-height: 0; transition: max-height 0.3s ease; }
        .submenu.open { max-height: 500px; }
        .s-arrow { transition: transform 0.22s ease; font-size: 0.55rem !important; opacity: 0.5; }

        /* ── Divider ── */
        .sdiv { border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 8px 0; }

        /* ══════════════════════════════
           HEADER
        ══════════════════════════════ */
        .admin-header {
            background: rgba(255,255,255,0.94);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #d8e8d3;
            box-shadow: 0 2px 0 rgba(76,175,80,0.08), 0 8px 24px rgba(0,0,0,0.04);
        }
        .top-stripe {
            height: 3px;
            background: linear-gradient(90deg, #0D1F03 0%, #2d7a2d 40%, #4CAF50 70%, #7BE07B 100%);
        }

        /* ── User pill ── */
        .u-pill {
            display: flex; align-items: center; gap: 7px;
            background: linear-gradient(135deg, #f5fff2, #e5f5df);
            border: 1px solid #c7e6c0; border-radius: 50px;
            padding: 4px 11px 4px 5px; cursor: pointer;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }
        .u-pill:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(76,175,80,0.18); }

        /* ── Search box ── */
        .search-box {
            display: flex; align-items: center; gap: 7px;
            background: #f5fbf2; border: 1px solid #cfe6cb;
            border-radius: 12px; padding: 7px 13px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }
        .search-box:focus-within {
            background: #ffffff; border-color: #4CAF50;
            box-shadow: 0 0 0 4px rgba(76,175,80,0.12);
        }
        .search-box input {
            background: transparent; outline: none; border: none;
            font-size: 0.82rem; color: #223322; width: 150px;
        }
        .search-box input::placeholder { color: #7b8e7a; }

        /* ── Botón icono header (notif, bandeja, etc.) ── */
        .header-icon-btn {
            position: relative;
            width: 34px; height: 34px;
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            background: #f5fbf2;
            border: 1px solid #cfe6cb;
            cursor: pointer;
            transition: background 0.18s ease, border-color 0.18s ease,
                        box-shadow 0.18s ease, transform 0.18s ease;
            flex-shrink: 0;
        }
        .header-icon-btn:hover {
            background: #e5f5df;
            border-color: #4CAF50;
            box-shadow: 0 4px 14px rgba(76,175,80,0.18);
            transform: translateY(-1px);
        }
        .header-icon-btn img { width: 16px; height: 16px; object-fit: contain; opacity: 0.75; }
        .header-icon-btn:hover img { opacity: 1; }
        .header-icon-btn i { font-size: 0.82rem; color: #4a6e4a; }
        .header-icon-btn:hover i { color: #2d7a2d; }

        /* ══════════════════════════════
           FOOTER — limpio, solo copyright
        ══════════════════════════════ */
        .admin-footer {
            background: linear-gradient(180deg, #112507 0%, #0D1F03 100%);
            border-top: 1px solid rgba(111,255,111,0.08);
            box-shadow: 0 -6px 30px rgba(0,0,0,0.25);
        }

        /* ══════════════════════════════
           DROPDOWNS
        ══════════════════════════════ */
        .dropdown-panel {
            background: rgba(255,255,255,0.98);
            border: 1px solid #d9ead4;
            border-radius: 18px;
            box-shadow: 0 10px 34px rgba(0,0,0,0.12);
            backdrop-filter: blur(12px);
        }

        /* ── Badge pulse ── */
        @keyframes badge-pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.18)} }
        .bp { animation: badge-pulse 2s infinite; }

        /* ── Overlay ── */
        #sidebar-overlay {
            transition: opacity 0.28s;
            background: rgba(0,0,0,0.42);
            backdrop-filter: blur(2px);
        }
    </style>

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
            <a href="{{ route('admin.usuarios.index') }}" class="nav-item" style="font-size:0.77rem; padding:6px 10px;">
                <i class="fas fa-users ni" style="font-size:0.68rem;"></i>
                <span>Gestión de usuarios</span>
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
            <a href="{{ route('admin.reportes.index') }}" class="nav-item" style="font-size:0.77rem; padding:6px 10px;">
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

        <a href="{{ route('admin.faq.index') }}" class="nav-item">
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
        <a href="#" onclick="document.getElementById('form-logout').submit(); return false;" title="Cerrar sesión"
           style="color:#58744f;" class="flex-shrink-0 transition-colors"
           onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#58744f'">
            <i class="fas fa-sign-out-alt text-sm"></i>
        </a>
        <form id="form-logout" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
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