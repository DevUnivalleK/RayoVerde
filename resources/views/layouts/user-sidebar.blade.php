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

    <style>
        /* ══════════════════════════════
           PALETA USUARIO
           Sidebar: verde bosque oscuro
           #0e2a10 → #132e14 → #183818
           Acento:  #4CAF50 / #6fcf6f
           Fondo:   #eef2eb
        ══════════════════════════════ */

        /* ── Scrollbar sidebar ── */
        #sidebar-nav::-webkit-scrollbar       { width: 3px; }
        #sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        #sidebar-nav::-webkit-scrollbar-thumb { background: #4CAF50; border-radius: 99px; }

        /* ── Scrollbar global ── */
        ::-webkit-scrollbar       { width: 6px; }
        ::-webkit-scrollbar-track { background: #eef4ea; }
        ::-webkit-scrollbar-thumb { background: #4CAF50; border-radius: 99px; }

        /* ── Base ── */
        body { background: #eef2eb; font-family: 'DM Sans', sans-serif; color: #1d2b1a; }

        /* ══════════════════════════════
           SIDEBAR — verde bosque oscuro
           Diferente al admin (#0D1F03 casi negro)
           Aquí es un verde profundo reconocible
        ══════════════════════════════ */
        #sidebar {
            width: 224px;
            background: linear-gradient(180deg,
                #0e2a10 0%,
                #132e14 40%,
                #183818 100%
            );
            border-right: 1px solid rgba(111,255,111,0.07);
            box-shadow: 5px 0 22px rgba(0,0,0,0.28);
            transition: transform 0.28s cubic-bezier(0.4,0,0.2,1);
        }
        @media (max-width: 1023px) {
            #sidebar.sidebar-hidden { transform: translateX(-100%); }
        }

        /* ── Logo area ── */
        .sidebar-brand {
            border-bottom: 1px solid rgba(255,255,255,0.06);
            background: rgba(255,255,255,0.02);
        }

        /* ── Badge verde "cliente" en logo ── */
        .brand-badge {
            font-size: 0.55rem; letter-spacing: 0.1em; text-transform: uppercase;
            font-weight: 700; color: #4CAF50;
            background: rgba(76,175,80,0.12);
            border: 1px solid rgba(76,175,80,0.25);
            border-radius: 99px; padding: 1px 7px;
            display: inline-block; margin-top: 3px;
        }

        /* ── Nav items ── */
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 12px;
            font-size: 0.84rem; font-weight: 500;
            color: #9fbf9a;
            text-decoration: none; cursor: pointer; width: 100%;
            transition: background 0.18s ease, color 0.18s ease,
                        transform 0.18s ease;
        }
        .nav-item .ni {
            width: 16px; text-align: center; font-size: 0.76rem;
            opacity: 0.6; flex-shrink: 0;
            transition: opacity 0.18s ease;
        }
        .nav-item:hover {
            background: rgba(76,175,80,0.13);
            color: #dfffdc;
            transform: translateX(2px);
        }
        .nav-item:hover .ni { opacity: 1; }

        /* Activo — acento verde brillante, diferente al admin
           (admin usa gradiente más azulado/oscuro)  */
        .nav-item.active {
            background: linear-gradient(135deg,
                #1e6b2e 0%,
                #27ae60 50%,
                #4CAF50 100%
            );
            color: #ffffff;
            box-shadow: 0 4px 16px rgba(76,175,80,0.32),
                        inset 0 1px 0 rgba(255,255,255,0.12);
        }
        .nav-item.active .ni { opacity: 1; color: #fff; }

        /* ── Section labels ── */
        .slabel {
            font-size: 0.57rem; letter-spacing: 0.14em; text-transform: uppercase;
            color: #3d6040; font-weight: 700; padding: 13px 11px 4px;
        }

        /* ── Divider ── */
        .sdiv { border: none; border-top: 1px solid rgba(255,255,255,0.05); margin: 7px 0; }

        /* ── Submenu ── */
        .submenu { overflow: hidden; max-height: 0; transition: max-height 0.28s ease; }
        .submenu.open { max-height: 400px; }
        .s-arrow { transition: transform 0.22s ease; font-size: 0.55rem !important; opacity: 0.4; }

        /* ── Carrito badge sidebar ── */
        .cart-badge {
            background: #4CAF50; color: #fff;
            font-size: 0.56rem; font-weight: 700;
            min-width: 16px; height: 16px; border-radius: 99px;
            display: flex; align-items: center; justify-content: center; padding: 0 4px;
        }

        /* ── Perfil bottom ── */
        .sidebar-bottom {
            border-top: 1px solid rgba(255,255,255,0.05);
            background: #0a1f0c;
        }

        /* ══════════════════════════════
           HEADER
        ══════════════════════════════ */
        .user-header {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #d4edda;
            box-shadow: 0 2px 0 rgba(39,174,96,0.07),
                        0 6px 20px rgba(0,0,0,0.05);
        }

        /* Franja superior — más verde que el admin para distinguirse */
        .top-stripe {
            height: 3px;
            background: linear-gradient(90deg,
                #0e2a10 0%,
                #27ae60 45%,
                #4CAF50 72%,
                #7BE07B 100%
            );
        }

        /* ── Search ── */
        .search-box {
            display: flex; align-items: center; gap: 7px;
            background: #f5fbf2; border: 1px solid #c3e6cb;
            border-radius: 12px; padding: 7px 13px;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .search-box:focus-within {
            background: #fff; border-color: #4CAF50;
            box-shadow: 0 0 0 4px rgba(76,175,80,0.1);
        }
        .search-box input {
            background: transparent; outline: none; border: none;
            font-size: 0.82rem; color: #1d2b1a; width: 150px;
        }
        .search-box input::placeholder { color: #7dcba8; }

        /* ── Header icon buttons ── */
        .hbtn {
            position: relative;
            width: 34px; height: 34px; border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            background: #f5fbf2; border: 1px solid #c3e6cb;
            cursor: pointer; flex-shrink: 0; text-decoration: none;
            transition: background 0.18s, border-color 0.18s,
                        box-shadow 0.18s, transform 0.18s;
        }
        .hbtn:hover {
            background: #e0f2e8; border-color: #27ae60;
            box-shadow: 0 4px 14px rgba(39,174,96,0.18);
            transform: translateY(-1px);
        }
        .hbtn i   { font-size: 0.82rem; color: #3a7a50; }
        .hbtn img { width: 16px; height: 16px; object-fit: contain; opacity: 0.72; }
        .hbtn:hover img { opacity: 1; }
        .hbtn:hover i { color: #1a5c30; }

        /* ── User pill ── */
        .u-pill {
            display: flex; align-items: center; gap: 7px;
            background: linear-gradient(135deg, #f5fff2, #e5f5df);
            border: 1px solid #c3e6cb; border-radius: 50px;
            padding: 4px 11px 4px 5px; cursor: pointer;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .u-pill:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(39,174,96,0.18);
        }

        /* ── Badge pulse ── */
        @keyframes badge-pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.18)} }
        .bp { animation: badge-pulse 2s infinite; }

        /* ── Dropdowns ── */
        .dropdown-panel {
            background: rgba(255,255,255,0.98);
            border: 1px solid #d4edda; border-radius: 18px;
            box-shadow: 0 10px 34px rgba(0,0,0,0.11);
            backdrop-filter: blur(12px);
        }

        /* ── Overlay ── */
        #sidebar-overlay { transition: opacity 0.28s; backdrop-filter: blur(2px); }

        /* ══════════════════════════════
           FOOTER
        ══════════════════════════════ */
        .user-footer {
            background: linear-gradient(180deg, #0e2a10 0%, #0a1c0c 100%);
            border-top: 1px solid rgba(76,175,80,0.12);
            box-shadow: 0 -5px 24px rgba(0,0,0,0.2);
        }
    </style>

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
                        <span class="bp absolute -top-1 -right-1 w-[15px] h-[15px] rounded-full
                                     flex items-center justify-center text-white font-bold"
                              style="background:#ef4444; font-size:0.5rem;">2</span>
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
                        <div style="max-height:230px; overflow-y:auto;">
                            <div class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer"
                                 style="border-bottom:1px solid #f0faf4;">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                                     style="background:#d9f2e4;">
                                    <i class="fas fa-check-circle" style="color:#27ae60; font-size:0.65rem;"></i>
                                </div>
                                <div>
                                    <p style="font-size:0.82rem; font-weight:600; color:#1d2b1a;">Pedido #1042 aceptado</p>
                                    <p style="font-size:0.73rem; color:#3a6040; margin-top:2px;">Tu pedido está en preparación</p>
                                    <p style="font-size:0.68rem; color:#7dcba8; margin-top:3px;">Hace 10 min</p>
                                </div>
                            </div>
                            <div class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                                     style="background:#e8f5e0;">
                                    <i class="fas fa-file-invoice-dollar" style="color:#27ae60; font-size:0.65rem;"></i>
                                </div>
                                <div>
                                    <p style="font-size:0.82rem; font-weight:600; color:#1d2b1a;">Cotización lista</p>
                                    <p style="font-size:0.73rem; color:#3a6040; margin-top:2px;">Tu cotización #08 fue procesada</p>
                                    <p style="font-size:0.68rem; color:#7dcba8; margin-top:3px;">Hace 2 horas</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-center py-2"
                             style="background:#f5fbf2; border-top:1px solid #c3e6cb;">
                            <a href="{{ url('/mis-cotizaciones') }}"
                               style="font-size:0.75rem; font-weight:600; color:#1e6b2e;">
                                Ver todas
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