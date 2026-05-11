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
        <a href="/admin/reportes" class="text-green-700 font-semibold border-b-2 border-green-700 pb-2">Dashboard</a>
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
    let ultimaActualizacion = null;
    let intervaloActualizacion = null;

    function cargarFiltros() {
        fetch('/admin/reportes/filtros')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.filtros) {
                    const clienteSelect = document.getElementById('filtro_cliente');
                    if (data.filtros.clientes) {
                        data.filtros.clientes.forEach(c => {
                            clienteSelect.innerHTML += `<option value="${c.id_cliente}">${c.empresa || c.nombre}</option>`;
                        });
                    }
                    
                    const productoSelect = document.getElementById('filtro_producto');
                    if (data.filtros.productos) {
                        data.filtros.productos.forEach(p => {
                            productoSelect.innerHTML += `<option value="${p.id_producto}">${p.nombre}</option>`;
                        });
                    }
                    
                    const estadoSelect = document.getElementById('filtro_estado');
                    if (data.filtros.estados) {
                        data.filtros.estados.forEach(e => {
                            estadoSelect.innerHTML += `<option value="${e.id_estado}">${e.nombre_estado}</option>`;
                        });
                    }
                }
            })
            .catch(error => console.error('Error cargando filtros:', error));
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
        
        fetch(`/admin/reportes/filtrado?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.resumen) {
                        document.getElementById('total_cotizaciones').innerText = data.resumen.total_cotizaciones || 0;
                        document.getElementById('total_ventas').innerText = `$${data.resumen.total_ventas || 0}`;
                        document.getElementById('promedio_venta').innerText = `$${data.resumen.promedio || 0}`;
                        document.getElementById('total_descuentos').innerText = `$${data.resumen.total_descuentos || 0}`;
                    }
                    
                    if (data.evolucion && data.evolucion.length > 0) {
                        actualizarGraficoEvolucion(data.evolucion);
                    }
                    
                    if (data.cotizaciones) {
                        actualizarGraficoProductos(data.cotizaciones);
                        actualizarTabla(data.cotizaciones);
                    }
                }
            })
            .catch(error => console.error('Error cargando reporte:', error));
    }
    
    function actualizarGraficoEvolucion(evolucion) {
        const ctx = document.getElementById('chartEvolucion').getContext('2d');
        if (chartEvolucion) chartEvolucion.destroy();
        
        const fechas = evolucion.map(e => e.fecha);
        const totales = evolucion.map(e => e.total);
        
        chartEvolucion = new Chart(ctx, {
            type: 'line',
            data: {
                labels: fechas,
                datasets: [
                    {
                        label: 'N° Cotizaciones',
                        data: totales,
                        borderColor: '#006c0f',
                        backgroundColor: 'rgba(0,108,15,0.1)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    }
    
    function actualizarGraficoProductos(cotizaciones) {
        const productos = {};
        
        cotizaciones.forEach(c => {
            if (c.detalles && c.detalles.length > 0) {
                c.detalles.forEach(d => {
                    const nombre = d.producto?.nombre || 'Producto';
                    productos[nombre] = (productos[nombre] || 0) + (parseFloat(d.subtotal) || 0);
                });
            }
        });
        
        const ctx = document.getElementById('chartProductos').getContext('2d');
        if (chartProductos) chartProductos.destroy();
        
        const labels = Object.keys(productos).slice(0, 5);
        const valores = Object.values(productos).slice(0, 5);
        
        chartProductos = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ventas ($)',
                    data: valores,
                    backgroundColor: '#64b863',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    }
    
    function actualizarTabla(cotizaciones) {
        const tbody = document.getElementById('tabla-cotizaciones');
        if (!cotizaciones || cotizaciones.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-gray-500">No hay cotizaciones</td>' . ';
            return;
        }
        
        tbody.innerHTML = cotizaciones.map(c => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-sm">${c.codigo || '-'}${c.codigo || '-'}_
                <td class="px-4 py-3 text-sm">${c.generado_en ? new Date(c.generado_en).toLocaleDateString() : '-'}${c.generado_en ? new Date(c.generado_en).toLocaleDateString() : '-'}_
                <td class="px-4 py-3 text-sm">${c.cliente?.empresa || c.cliente?.nombre || 'N/A'}${c.cliente?.empresa || c.cliente?.nombre || 'N/A'}_
                <td class="px-4 py-3 text-sm">$${parseFloat(c.subtotal || 0).toFixed(2)}${parseFloat(c.subtotal || 0).toFixed(2)}_
                <td class="px-4 py-3 text-sm">$${parseFloat(c.descuento_aplicado || 0).toFixed(2)}${parseFloat(c.descuento_aplicado || 0).toFixed(2)}_
                <td class="px-4 py-3 text-sm font-bold">$${parseFloat(c.total || 0).toFixed(2)}${parseFloat(c.total || 0).toFixed(2)}_
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded-full ${getEstadoClass(c.id_estado)}">
                        ${getEstadoTexto(c.id_estado)}
                    </span>
                 </td>
             </tr>
        `).join('');
    }

    function getEstadoClass(idEstado) {
        const estados = {1: 'bg-yellow-100 text-yellow-800', 2: 'bg-green-100 text-green-800', 3: 'bg-red-100 text-red-800'};
        return estados[idEstado] || 'bg-gray-100 text-gray-800';
    }

    function getEstadoTexto(idEstado) {
        const estados = {1: 'Pendiente', 2: 'Aprobada', 3: 'Rechazada', 4: 'Expirada'};
        return estados[idEstado] || 'Desconocido';
    }
    
    // Configurar fechas por defecto
    const hoy = new Date();
    const hace30Dias = new Date();
    hace30Dias.setDate(hoy.getDate() - 30);
    document.getElementById('fecha_inicio').valueAsDate = hace30Dias;
    document.getElementById('fecha_fin').valueAsDate = hoy;
    
    // Eventos de botones
    document.getElementById('btn-filtrar').addEventListener('click', cargarReporte);
    
    document.getElementById('btn-exportar-excel').addEventListener('click', () => {
        const params = new URLSearchParams();
        if (document.getElementById('fecha_inicio').value) params.append('fecha_inicio', document.getElementById('fecha_inicio').value);
        if (document.getElementById('fecha_fin').value) params.append('fecha_fin', document.getElementById('fecha_fin').value);
        if (document.getElementById('filtro_cliente').value) params.append('id_cliente', document.getElementById('filtro_cliente').value);
        if (document.getElementById('filtro_producto').value) params.append('id_producto', document.getElementById('filtro_producto').value);
        if (document.getElementById('filtro_estado').value) params.append('id_estado', document.getElementById('filtro_estado').value);
        window.location.href = `/admin/reportes/exportar-excel?${params.toString()}`;
    });
    
    document.getElementById('btn-exportar-pdf').addEventListener('click', () => {
        const params = new URLSearchParams();
        if (document.getElementById('fecha_inicio').value) params.append('fecha_inicio', document.getElementById('fecha_inicio').value);
        if (document.getElementById('fecha_fin').value) params.append('fecha_fin', document.getElementById('fecha_fin').value);
        if (document.getElementById('filtro_cliente').value) params.append('id_cliente', document.getElementById('filtro_cliente').value);
        if (document.getElementById('filtro_producto').value) params.append('id_producto', document.getElementById('filtro_producto').value);
        if (document.getElementById('filtro_estado').value) params.append('id_estado', document.getElementById('filtro_estado').value);
        window.location.href = `/admin/reportes/exportar-pdf?${params.toString()}`;
    });
    
    // Botón PDF Detallado
    function agregarBotonExportarDetallado() {
        const botonera = document.querySelector('.flex.gap-2.items-end');
        if (botonera && !document.getElementById('btn-exportar-pdf-detallado')) {
            const btn = document.createElement('button');
            btn.id = 'btn-exportar-pdf-detallado';
            btn.className = 'bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700';
            btn.innerHTML = '<i class="fas fa-file-pdf"></i> PDF Detallado';
            btn.onclick = () => {
                const params = new URLSearchParams();
                if (document.getElementById('fecha_inicio').value) params.append('fecha_inicio', document.getElementById('fecha_inicio').value);
                if (document.getElementById('fecha_fin').value) params.append('fecha_fin', document.getElementById('fecha_fin').value);
                window.location.href = `/admin/reportes/exportar-pdf-detallado?${params.toString()}`;
            };
            botonera.appendChild(btn);
        }
    }
    
    // Live Updates
    function iniciarLiveUpdates() {
        intervaloActualizacion = setInterval(verificarNovedades, 30000);
    }
    
    function verificarNovedades() {
        const params = new URLSearchParams();
        params.append('ultima_actualizacion', ultimaActualizacion || '');
        
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        if (fechaInicio) params.append('fecha_inicio', fechaInicio);
        if (fechaFin) params.append('fecha_fin', fechaFin);
        
        fetch(`/admin/reportes/realtime?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.hay_novedades) {
                    mostrarNotificacion('📊 ¡Hay nuevos datos! Actualizando reporte...');
                    cargarReporte();
                    ultimaActualizacion = data.timestamp;
                }
            })
            .catch(error => console.error('Error en live updates:', error));
    }
    
    function mostrarNotificacion(mensaje) {
        const notificacion = document.createElement('div');
        notificacion.className = 'fixed top-20 right-4 bg-green-600 text-white px-4 py-2 rounded shadow-lg z-50';
        notificacion.innerHTML = `<i class="fas fa-sync-alt mr-2"></i>${mensaje}`;
        document.body.appendChild(notificacion);
        setTimeout(() => notificacion.remove(), 3000);
    }
    
    // Inicializar todo
    document.addEventListener('DOMContentLoaded', () => {
        cargarFiltros();
        cargarReporte();
        iniciarLiveUpdates();
        agregarBotonExportarDetallado();
    });
    
    window.addEventListener('beforeunload', () => {
        if (intervaloActualizacion) clearInterval(intervaloActualizacion);
    });
</script>

</body>
</html>