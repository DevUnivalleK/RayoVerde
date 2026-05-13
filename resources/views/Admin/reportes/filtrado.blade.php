@extends('layouts.admin-sidebar')

@section('title', 'Reporte Filtrado')
@section('breadcrumb', 'Reportes / Filtrado')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-green-700 mb-6">Reporte Filtrado</h1>
    
    <div class="bg-white p-4 rounded shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Fecha Inicio</label>
                <input type="date" id="fecha_inicio" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Fecha Fin</label>
                <input type="date" id="fecha_fin" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Usuario</label>
                <select id="filtro_usuario" class="w-full border rounded px-3 py-2">
                    <option value="">Todos</option>
                </select>
            </div>
            <div class="flex gap-2 items-end">
                <button id="btn-filtrar" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    <i class="fas fa-search"></i> Filtrar
                </button>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3">Código</th>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Usuario</th>
                    <th class="px-4 py-3">Subtotal</th>
                    <th class="px-4 py-3">Total</th>
                </tr>
            </thead>
            <tbody id="tabla-cotizaciones" class="divide-y">
                <tr><td colspan="5" class="text-center py-4">Cargando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
function cargarUsuarios() {
    fetch('/admin/reportes/usuarios')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('filtro_usuario');
            data.forEach(u => {
                select.innerHTML += `<option value="${u.id_usuario}">${u.nombre} ${u.apellido}</option>`;
            });
        });
}

function cargarReporte() {
    const params = new URLSearchParams();
    if (document.getElementById('fecha_inicio').value) params.append('fecha_inicio', document.getElementById('fecha_inicio').value);
    if (document.getElementById('fecha_fin').value) params.append('fecha_fin', document.getElementById('fecha_fin').value);
    if (document.getElementById('filtro_usuario').value) params.append('id_usuario', document.getElementById('filtro_usuario').value);
    
    fetch(`/admin/reportes/filtrado-data?${params.toString()}`)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('tabla-cotizaciones');
            if (data.cotizaciones.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4">No hay datos</td></tr>';
            } else {
                tbody.innerHTML = data.cotizaciones.map(c => `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">${c.codigo}${c.codigo}</td>
                        <td class="px-4 py-3">${new Date(c.generado_en).toLocaleDateString()}${new Date(c.generado_en).toLocaleDateString()}</td>
                        <td class="px-4 py-3">${c.usuario?.nombre ||'N/A'}</td>
                        <td class="px-4 py-3">Bs ${c.subtotal}</td>
                        <td class="px-4 py-3">Bs ${c.total}</td>
                    </tr>
                `).join('');
            }
        });
}

const hoy = new Date();
const hace30Dias = new Date();
hace30Dias.setDate(hoy.getDate() - 30);
document.getElementById('fecha_inicio').valueAsDate = hace30Dias;
document.getElementById('fecha_fin').valueAsDate = hoy;

cargarUsuarios();
document.getElementById('btn-filtrar').addEventListener('click', cargarReporte);
cargarReporte();
</script>
@endsection