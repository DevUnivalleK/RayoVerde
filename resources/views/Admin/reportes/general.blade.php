@extends('layouts.admin-sidebar')

@section('title', 'Reporte General')
@section('breadcrumb', 'Reportes / General')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-green-700 mb-6">Reporte General</h1>
    
    <!-- Filtros -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Fecha Inicio</label>
                <input type="date" id="fecha_inicio" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Fecha Fin</label>
                <input type="date" id="fecha_fin" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex gap-2 items-end">
                <button id="btn-filtrar" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    <i class="fas fa-search"></i> Filtrar
                </button>
                <button id="btn-excel" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button id="btn-pdf" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
            </div>
        </div>
    </div>
    
    <!-- Tarjetas de métricas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4">
            <div class="text-gray-500 text-sm">Total Cotizaciones</div>
            <div class="text-2xl font-bold text-green-700" id="total_cotizaciones">0</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-gray-500 text-sm">Total Ventas</div>
            <div class="text-2xl font-bold text-green-700" id="total_ventas">Bs 0</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-gray-500 text-sm">Promedio</div>
            <div class="text-2xl font-bold text-green-700" id="promedio">Bs 0</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-gray-500 text-sm">Descuentos</div>
            <div class="text-2xl font-bold text-red-600" id="total_descuentos">Bs 0</div>
        </div>
    </div>
    
    <!-- Tabla -->
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Código</th>
                    <th class="px-4 py-3 text-left">Fecha</th>
                    <th class="px-4 py-3 text-left">Usuario</th>
                    <th class="px-4 py-3 text-left">Subtotal</th>
                    <th class="px-4 py-3 text-left">Total</th>
                    <th class="px-4 py-3 text-left">Estado</th>
                </tr>
            </thead>
            <tbody id="tabla-cotizaciones" class="divide-y">
                <tr><td colspan="6" class="text-center py-4">Cargando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
function cargarReporte() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    let url = '/admin/reportes/filtrado-data';
    if (fechaInicio || fechaFin) {
        url += `?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    }
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('total_cotizaciones').innerText = data.resumen.total_cotizaciones || 0;
                document.getElementById('total_ventas').innerText = 'Bs ' + (parseFloat(data.resumen.total_ventas).toFixed(2) || 0);
                document.getElementById('promedio').innerText = 'Bs ' + (parseFloat(data.resumen.promedio).toFixed(2) || 0);
                document.getElementById('total_descuentos').innerText = 'Bs ' + (parseFloat(data.resumen.total_descuentos).toFixed(2) || 0);
                
                const tbody = document.getElementById('tabla-cotizaciones');
                if (data.cotizaciones.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4">No hay datos</td></tr>';
                } else {
                    tbody.innerHTML = data.cotizaciones.map(c => `
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">${c.codigo}${c.codigo}</td>
                            <td class="px-4 py-3">${new Date(c.generado_en).toLocaleDateString()}${new Date(c.generado_en).toLocaleDateString()}</td>
                            <td class="px-4 py-3">${c.usuario?.nombre || 'N/A'}</td>
                            <td class="px-4 py-3">Bs ${c.subtotal}</td>
                            <td class="px-4 py-3 font-bold">Bs ${c.total}</td>
                            <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-yellow-100">Pendiente</span></td>
                        </tr>
                    `).join('');
                }
            }
        });
}

// Exportar Excel
document.getElementById('btn-excel')?.addEventListener('click', () => {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    window.location.href = `/admin/reportes/exportar/excel?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
});

// Exportar PDF
document.getElementById('btn-pdf')?.addEventListener('click', () => {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    window.location.href = `/admin/reportes/exportar/pdf?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
});

// Fechas por defecto
const hoy = new Date();
const hace30Dias = new Date();
hace30Dias.setDate(hoy.getDate() - 30);
document.getElementById('fecha_inicio').valueAsDate = hace30Dias;
document.getElementById('fecha_fin').valueAsDate = hoy;

document.getElementById('btn-filtrar').addEventListener('click', cargarReporte);
cargarReporte();
</script>
@endsection