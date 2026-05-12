@extends('layouts.user-sidebar')

@section('title', 'Inicio')

@section('breadcrumb', 'Panel de Control')

@push('styles')
<style>
    /* Estilos extra para las tarjetas de productos */
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        transition: all 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-5px);
        border-color: #4CAF50;
        box-shadow: 0 12px 24px rgba(39, 174, 96, 0.1);
    }
    .hero-gradient {
        background: linear-gradient(135deg, #132e14 0%, #1e6b2e 100%);
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-10">

    {{-- ── SECCIÓN HERO / BIENVENIDA ── --}}
    <section class="hero-gradient rounded-[2.5rem] p-8 md:p-12 text-white relative overflow-hidden shadow-2xl">
        <div class="relative z-10 md:w-2/3">
            <span class="bg-white/10 text-emerald-300 text-xs font-bold px-3 py-1 rounded-full tracking-widest uppercase mb-4 inline-block border border-white/10">
                Natural & Orgánico
            </span>
            <h1 class="font-display text-4xl md:text-5xl lg:text-6xl mb-6 leading-tight">
                Bienvenido, <span class="text-emerald-400">{{ session('usuario_nombre', 'Cliente') }}</span>
            </h1>
            <p class="text-emerald-100/80 text-lg mb-8 leading-relaxed max-w-md">
                Descubre la pureza de nuestros aceites esenciales, prensados en frío para mantener todas sus propiedades intactas.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('cliente.catalogo') }}" class="bg-[#4CAF50] hover:bg-[#3d8b40] text-white px-8 py-3.5 rounded-2xl font-bold transition-all shadow-lg shadow-emerald-900/40">
                    Ir al Catálogo
                </a>
                <a href="{{ route('chatbot.index') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/20 px-8 py-3.5 rounded-2xl font-bold transition-all">
                    Consultar con RayoBot
                </a>
            </div>
        </div>
        
        {{-- Imagen decorativa absoluta --}}
        <div class="absolute right-[-10%] bottom-[-20%] w-[60%] opacity-20 pointer-events-none">
            <i class="fas fa-leaf text-[250px] rotate-45"></i>
        </div>
    </section>

    {{-- ── SECCIÓN ESTADÍSTICAS RÁPIDAS ── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-card p-6 rounded-3xl flex items-center gap-5">
            <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 shadow-inner">
                <i class="fas fa-shopping-basket text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-emerald-800/60 font-bold uppercase tracking-wider">Mi Carrito</p>
                <p class="text-2xl font-display text-emerald-900 leading-none">
                    {{ session('carrito') ? count(session('carrito')) : 0 }} <span class="text-sm font-sans font-medium text-emerald-600">Items</span>
                </p>
            </div>
        </div>

        <div class="glass-card p-6 rounded-3xl flex items-center gap-5">
            <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 shadow-inner">
                <i class="fas fa-file-invoice text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-blue-800/60 font-bold uppercase tracking-wider">Cotizaciones</p>
                <p class="text-2xl font-display text-blue-900 leading-none">
                    3 <span class="text-sm font-sans font-medium text-blue-600">Activas</span>
                </p>
            </div>
        </div>

        <div class="glass-card p-6 rounded-3xl flex items-center gap-5">
            <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 shadow-inner">
                <i class="fas fa-shipping-fast text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-orange-800/60 font-bold uppercase tracking-wider">Último Pedido</p>
                <p class="text-lg font-bold text-orange-900 leading-none truncate">En camino...</p>
            </div>
        </div>
    </div>

    {{-- ── PRODUCTOS DESTACADOS ── --}}
    <section>
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="font-display text-3xl text-[#0e2a10]">Lo más buscado</h2>
                <div class="h-1 w-12 bg-[#4CAF50] rounded-full mt-2"></div>
            </div>
            <a href="{{ route('cliente.catalogo') }}" class="group text-sm font-bold text-[#4CAF50] flex items-center gap-2">
                Ver todo el catálogo <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Card de Producto --}}
            @forelse([1,2,3,4] as $item) {{-- Simulación de productos --}}
            <div class="group relative bg-white rounded-[2rem] p-4 shadow-sm border border-emerald-100/50 hover:shadow-xl transition-all overflow-hidden">
                <div class="aspect-square bg-[#f5fbf2] rounded-2xl mb-4 overflow-hidden relative">
                    <img src="https://via.placeholder.com/200" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <button class="absolute top-3 right-3 w-8 h-8 bg-white/80 backdrop-blur shadow-sm rounded-full flex items-center justify-center text-red-400 hover:bg-red-500 hover:text-white transition-colors">
                        <i class="far fa-heart text-xs"></i>
                    </button>
                </div>
                <h3 class="font-bold text-emerald-900 mb-1">Aceite de Moringa</h3>
                <p class="text-xs text-emerald-600/70 mb-3">Prensado en frío · 250ml</p>
                <div class="flex items-center justify-between">
                    <span class="text-xl font-display text-emerald-900">45.00 <small class="text-xs font-sans text-emerald-600">BOB</small></span>
                    <button class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center hover:bg-[#4CAF50] hover:text-white transition-all shadow-sm">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                </div>
            </div>
            @empty
                <p>No hay productos destacados por ahora.</p>
            @endforelse
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
    console.log('Página de inicio de Rayo Verde cargada');
</script>
@endpush