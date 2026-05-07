<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rayo Verde - Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- HEADER -->
    <div class="flex justify-between items-center border-b border-gray-300 pb-4 mb-4">
        <h1 class="text-3xl font-bold text-green-700">RAYO VERDE</h1>
        <span class="text-gray-500 text-sm">Dashboard Administrativo</span>
    </div>

    <!-- NAVBAR -->
    <div class="flex space-x-6 mb-6 border-b pb-2">
        <a href="/" class="text-gray-500">Inicio</a>
        <a href="/mis-cotizaciones" class="text-gray-500">Mis Cotizaciones</a>
        <a href="/reportes" class="text-green-700 font-semibold border-b-2 border-green-700 pb-2">Dashboard</a>
        <a href="#" class="text-gray-500 ml-auto">Cerrar Sesión</a>
    </div>

    <!-- FILTROS DINÁMICOS -->
    <div class="bg-white p-4 rounded shadow-sm mb-6">
        <h3 class="text-lg font-semibold mb-3">Filtros Dinámicos</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                <input type="date" id="fecha_inicio" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                <input type="date" id="fecha_fin" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                <select id="filtro_cliente" class="w-full border rounded px-3 py-2">
                    <option value="">Todos</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Producto</label>
                <select id="filtro_producto" class="w-full border rounded px-3 py-2">
                    <option value="">Todos</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select id="filtro_estado" class="w-full border rounded px-3 py-2">
                    <option value="">Todos</option>
                </select>
            </div>
            <div class="flex gap-2 items-end">
                <button id="btn-filtrar" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    <i class="fas fa-search"></i> Filtrar
                </button>
                <button id="btn-exportar-excel" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button id="btn-exportar-pdf" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    <i class="fas fa-file-pdf"></i> PDF
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
            <h3 class="text-lg font-semibold mb-3">Evolución de Cotizaciones (Líneas)</h3>
            <canvas id="chartEvolucion" height="250"></canvas>
        </div>
        <div class="bg-white rounded shadow p-4">
            <h3 class="text-lg font-semibold mb-3">Top Productos (Barras)</h3>
            <canvas id="chartProductos" height="250"></canvas>
        </div>
    </div>

    <!-- TABLA DE COTIZACIONES -->
    <div class="bg-white rounded shadow overflow-hidden">
        <h3 class="text-lg font-semibold p-4 border-b">Lista de Cotizaciones</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                        <th class="px-4 py-3">Código</th>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Cliente</th>
                        <th class="px-4 py-3">Subtotal</th>
                        <th class="px-4 py-3">Descuento</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Estado</th>
                    </tr>
                </thead>
                <tbody id="tabla-cotizaciones" class="divide-y">
                    <tr><td colspan="7" class="text-center py-4 text-gray-500">Cargando...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let chartEvolucion, chartProductos;
    
    function cargarFiltros() {
        fetch('/api/reportes/filtros')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const clienteSelect = document.getElementById('filtro_cliente');
                    data.filtros.clientes.forEach(c => {
                        clienteSelect.innerHTML += `<option value="${c.id_cliente}">${c.empresa}</option>`;
                    });
                    
                    const productoSelect = document.getElementById('filtro_producto');
                    data.filtros.productos.forEach(p => {
                        productoSelect.innerHTML += `<option value="${p.id_producto}">${p.nombre}</option>`;
                    });
                    
                    const estadoSelect = document.getElementById('filtro_estado');
                    data.filtros.estados.forEach(e => {
                        estadoSelect.innerHTML += `<option value="${e.id_estado}">${e.nombre_estado}</option>`;
                    });
                }
            });
    }
    
    function cargarReporte() {
        const params = new URLSearchParams();
        
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        const idCliente = document.getElementById('filtro_cliente').value;
        const idProducto = document.getElementById('filtro_producto').value;
        const idEstado = document.getElementById('filtro_estado').value;
        
        if (fechaInicio) params.append('fecha_inicio', fechaInicio);
        if (fechaFin) params.append('fecha_fin', fechaFin);
        if (idCliente) params.append('id_cliente', idCliente);
        if (idProducto) params.append('id_producto', idProducto);
        if (idEstado) params.append('id_estado', idEstado);
        
        fetch(`/api/reportes/filtrado?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('total_cotizaciones').innerText = data.resumen.total_cotizaciones;
                    document.getElementById('total_ventas').innerText = `$${data.resumen.total_ventas || 0}`;
                    document.getElementById('promedio_venta').innerText = `$${data.resumen.promedio || 0}`;
                    document.getElementById('total_descuentos').innerText = `$${data.resumen.total_descuentos || 0}`;
                    
                    actualizarGraficoEvolucion(data.evolucion);
                    actualizarGraficoProductos(data.cotizaciones);
                    actualizarTabla(data.cotizaciones);
                }
            });
    }
    
    function actualizarGraficoEvolucion(evolucion) {
        const ctx = document.getElementById('chartEvolucion').getContext('2d');
        if (chartEvolucion) chartEvolucion.destroy();
        
        chartEvolucion = new Chart(ctx, {
            type: 'line',
            data: {
                labels: evolucion.map(e => e.fecha),
                datasets: [
                    {
                        label: 'N° Cotizaciones',
                        data: evolucion.map(e => e.total),
                        borderColor: '#006c0f',
                        backgroundColor: 'rgba(0,108,15,0.1)',
                        fill: true
                    }
                ]
            }
        });
    }
    
    function actualizarGraficoProductos(cotizaciones) {
        const productos = {};
        cotizaciones.forEach(c => {
            if (c.detalles) {
                c.detalles.forEach(d => {
                    const nombre = d.producto?.nombre || 'Producto';
                    productos[nombre] = (productos[nombre] || 0) + d.subtotal;
                });
            }
        });
        
        const ctx = document.getElementById('chartProductos').getContext('2d');
        if (chartProductos) chartProductos.destroy();
        
        chartProductos = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(productos).slice(0, 5),
                datasets: [{
                    label: 'Ventas ($)',
                    data: Object.values(productos).slice(0, 5),
                    backgroundColor: '#64b863',
                    borderRadius: 5
                }]
            }
        });
    }
    
    function actualizarTabla(cotizaciones) {
        const tbody = document.getElementById('tabla-cotizaciones');
        if (cotizaciones.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-gray-500">No hay cotizaciones</td></tr>';
            return;
        }
        
        tbody.innerHTML = cotizaciones.map(c => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-sm">${c.codigo}</td>
                <td class="px-4 py-3 text-sm">${new Date(c.generado_en).toLocaleDateString()}</td>
                <td class="px-4 py-3 text-sm">${c.cliente?.empresa || 'N/A'}</td>
                <td class="px-4 py-3 text-sm">$${c.subtotal}</td>
                <td class="px-4 py-3 text-sm">$${c.descuento_aplicado}</td>
                <td class="px-4 py-3 text-sm font-bold">$${c.total}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">${c.estado?.nombre_estado || 'Pendiente'}</span></td>
            </tr>
        `).join('');
    }
    
    // Configurar fechas por defecto
    const hoy = new Date();
    const hace30Dias = new Date();
    hace30Dias.setDate(hoy.getDate() - 30);
    document.getElementById('fecha_inicio').valueAsDate = hace30Dias;
    document.getElementById('fecha_fin').valueAsDate = hoy;
    
    document.getElementById('btn-filtrar').addEventListener('click', cargarReporte);
    document.getElementById('btn-exportar-excel').addEventListener('click', () => {
        const params = new URLSearchParams();
        params.append('fecha_inicio', document.getElementById('fecha_inicio').value);
        params.append('fecha_fin', document.getElementById('fecha_fin').value);
        window.location.href = `/api/reportes/exportar-excel?${params.toString()}`;
    });
    document.getElementById('btn-exportar-pdf').addEventListener('click', () => {
        const params = new URLSearchParams();
        params.append('fecha_inicio', document.getElementById('fecha_inicio').value);
        params.append('fecha_fin', document.getElementById('fecha_fin').value);
        window.location.href = `/api/reportes/exportar-pdf?${params.toString()}`;
    });
    
    cargarFiltros();
    cargarReporte();
</script>

</body>
</html>