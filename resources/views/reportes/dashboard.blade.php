<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rayo Verde - Panel de Reportes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- HEADER -->
    <div class="flex justify-between items-center border-b border-gray-300 pb-4 mb-4">
        <h1 class="text-3xl font-bold text-green-700">RAYO VERDE</h1>
        <span class="text-gray-500 text-sm">Panel de Reportes</span>
    </div>

    <!-- NAVBAR -->
    <div class="flex space-x-6 mb-6 border-b pb-2">
        <a href="/" class="text-gray-500">Inicio</a>
        <a href="/mis-cotizaciones" class="text-gray-500">Mis Cotizaciones</a>
        <a href="/reportes" class="text-green-700 font-semibold border-b-2 border-green-700 pb-2">Reportes</a>
        <a href="#" class="text-gray-500 ml-auto">Cerrar Sesión</a>
    </div>

    <!-- FILTROS -->
    <div class="bg-white p-4 rounded shadow-sm mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                <input type="date" id="fecha_inicio" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                <input type="date" id="fecha_fin" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex items-end">
                <button id="btn-filtrar" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                    <i class="fas fa-search"></i> Filtrar
                </button>
            </div>
        </div>
    </div>

    <!-- TARJETAS DE MÉTRICAS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4">
            <div class="text-gray-500 text-sm">Total Cotizaciones</div>
            <div class="text-2xl font-bold text-green-700" id="total_cotizaciones">0</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-gray-500 text-sm">Total Ventas</div>
            <div class="text-2xl font-bold text-green-700" id="total_ventas">$0</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-gray-500 text-sm">Promedio Venta</div>
            <div class="text-2xl font-bold text-green-700" id="promedio_venta">$0</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-gray-500 text-sm">Total Descuentos</div>
            <div class="text-2xl font-bold text-red-600" id="total_descuentos">$0</div>
        </div>
    </div>

    <!-- GRÁFICOS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded shadow p-4">
            <h3 class="text-lg font-semibold mb-3">Top Productos</h3>
            <canvas id="chartProductos" height="250"></canvas>
        </div>
        <div class="bg-white rounded shadow p-4">
            <h3 class="text-lg font-semibold mb-3">Cotizaciones por Estado</h3>
            <canvas id="chartEstados" height="250"></canvas>
        </div>
    </div>

    <!-- TABLA TOP CLIENTES -->
    <div class="bg-white rounded shadow overflow-hidden">
        <h3 class="text-lg font-semibold p-4 border-b">Top Clientes</h3>
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                    <th class="px-4 py-3">Cliente</th>
                    <th class="px-4 py-3">Cotizaciones</th>
                    <th class="px-4 py-3">Total Compras</th>
                </tr>
            </thead>
            <tbody id="tabla-clientes" class="divide-y">
                <tr><td colspan="3" class="text-center py-4 text-gray-500">Cargando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    let chartProductos, chartEstados;
    
    function cargarMetricas() {
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        
        let url = '/api/reportes/metricas';
        if (fechaInicio || fechaFin) {
            url += `?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
        }
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('total_cotizaciones').innerText = data.totales.total_cotizaciones || 0;
                    document.getElementById('total_ventas').innerText = '$' + (data.totales.total_ventas || 0);
                    document.getElementById('promedio_venta').innerText = '$' + (data.totales.promedio_venta || 0);
                    document.getElementById('total_descuentos').innerText = '$' + (data.totales.total_descuentos || 0);
                    
                    actualizarGraficoProductos(data.top_productos);
                    actualizarGraficoEstados(data.por_estado);
                    actualizarTablaClientes(data.top_clientes);
                }
            });
    }
    
    function actualizarGraficoProductos(productos) {
        const ctx = document.getElementById('chartProductos').getContext('2d');
        if (chartProductos) chartProductos.destroy();
        
        chartProductos = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: productos.map(p => p.nombre),
                datasets: [{
                    label: 'Total Ventas ($)',
                    data: productos.map(p => p.total_ventas),
                    backgroundColor: '#006c0f',
                    borderRadius: 5
                }]
            }
        });
    }
    
    function actualizarGraficoEstados(estados) {
        const ctx = document.getElementById('chartEstados').getContext('2d');
        if (chartEstados) chartEstados.destroy();
        
        chartEstados = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: estados.map(e => e.nombre_estado),
                datasets: [{
                    data: estados.map(e => e.total),
                    backgroundColor: ['#006c0f', '#64b863', '#f59e0b', '#ef4444']
                }]
            }
        });
    }
    
    function actualizarTablaClientes(clientes) {
        const tbody = document.getElementById('tabla-clientes');
        if (clientes.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-gray-500">No hay datos</td></tr>';
            return;
        }
        
        tbody.innerHTML = clientes.map(c => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">${c.empresa}_\n                <td class="px-4 py-3 text-center">${c.total_cotizaciones}_\n                <td class="px-4 py-3">$${c.total_compras}_\n            </tr>
        `).join('');
    }
    
    // Configurar fechas por defecto
    const hoy = new Date();
    const hace30Dias = new Date();
    hace30Dias.setDate(hoy.getDate() - 30);
    
    document.getElementById('fecha_inicio').valueAsDate = hace30Dias;
    document.getElementById('fecha_fin').valueAsDate = hoy;
    
    document.getElementById('btn-filtrar').addEventListener('click', cargarMetricas);
    cargarMetricas();
</script>

</body>
</html>