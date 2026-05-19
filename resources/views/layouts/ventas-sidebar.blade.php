<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rayo Verde - @yield('title')</title>
    <!-- Mismos estilos del Admin -->
    <link rel="stylesheet" href="{{ asset('css/estilosDash.css') }}">
    @stack('styles')
</head>
<body>

    <div class="dashboard-wrapper">
        {{-- SIdebar lateral adaptado para ventas --}}
        <aside class="rv-sidebar">
            <div class="sidebar-brand">
                <span class="brand-logo">RV</span>
                <span class="brand-sub">Módulo Ventas</span>
            </div>

            <nav class="sidebar-menu">
                <p class="menu-label">Dashboard</p>
                <a href="{{ route('ventas.dashboard') }}" class="{{ Request::is('ventas') ? 'active' : '' }}">
                    <img src="/images/dashboard.png" alt="" class="menu-icon"> Panel General
                </a>

                <p class="menu-label">Atención Digital</p>
                <a href="{{ route('ventas.chat.bandeja') }}" class="{{ Request::is('ventas/chat*') ? 'active' : '' }}">
                    <img src="/images/chat.png" alt="" class="menu-icon"> Bandeja de Chats
                </a>

                <p class="menu-label">Operaciones</p>
                <a href="{{ route('ventas.cotizaciones.index') }}" class="{{ Request::is('ventas/cotizaciones*') ? 'active' : '' }}">
                    <img src="/images/icono-ventas.png" alt="" class="menu-icon"> Cotizaciones
                </a>
            </nav>

            <div class="sidebar-user">
                <div class="user-avatar">
                    <!-- Primera letra del nombre del usuario logueado -->
                    {{ substr(Auth::user()->nombre ?? 'V', 0, 1) }}
                </div>
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->nombre ?? 'Personal Ventas' }}</span>
                    <span class="user-role">Ventas</span>
                </div>
                <!-- Formulario de Logout nativo -->
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-logout-icon" title="Cerrar Sesión" style="background:none; border:none; cursor:pointer;">
                        <img src="/images/logout.png" alt="Salir" style="width:20px;">
                    </button>
                </form>
            </div>
        </aside>

        {{-- Contenedor de contenido principal --}}
        <main class="main-content">
            <header class="content-header">
                <span class="breadcrumb-text">Módulo de Ventas / @yield('breadcrumb')</span>
            </header>

            <div class="content-body">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>