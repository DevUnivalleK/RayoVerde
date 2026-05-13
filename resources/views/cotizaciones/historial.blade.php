@extends('layouts.user-sidebar')

@section('title', 'Mis Cotizaciones')
@section('breadcrumb', 'Mis Cotizaciones')

@section('content')
<div class="bg-white rounded shadow overflow-hidden">
    <div class="p-4 border-b">
        <h2 class="text-xl font-bold text-green-700">Historial de Cotizaciones</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                    <th class="px-4 py-3">Código</th>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Subtotal</th>
                    <th class="px-4 py-3">Descuento</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-cotizaciones" class="divide-y">
                <tr><td colspan="7" class="text-center py-8 text-gray-500">Cargando cotizaciones...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
function getEstadoClass(idEstado) {
    const estados = {1: 'bg-yellow-100 text-yellow-800', 2: 'bg-green-100 text-green-800', 3: 'bg-red-100 text-red-800'};
    return estados[idEstado] || 'bg-gray-100 text-gray-800';
}
function getEstadoTexto(idEstado) {
    const estados = {1: 'Pendiente', 2: 'Aprobada', 3: 'Rechazada', 4: 'Expirada'};
    return estados[idEstado] || 'Desconocido';
}

fetch('/cotizaciones/historial')
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('tabla-cotizaciones');
        if (data.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-gray-500">No tienes cotizaciones registradas</td></tr>';
            return;
        }
        tbody.innerHTML = data.data.map(c => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm font-mono">${c.codigo}</td>
                <td class="px-4 py-3 text-sm">${new Date(c.generado_en).toLocaleDateString()}</td>
                <td class="px-4 py-3 text-sm">$${c.subtotal}</td>
                <td class="px-4 py-3 text-sm">$${c.descuento_aplicado}</td>
                <td class="px-4 py-3 text-sm font-bold">$${c.total}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full ${getEstadoClass(c.id_estado)}">${getEstadoTexto(c.id_estado)}</span></td>
                <td class="px-4 py-3 text-sm space-x-2">
                    <a href="/cotizaciones/${c.id_cotizacion}/pdf" class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">PDF</a>
                    <a href="/cotizaciones/${c.id_cotizacion}/excel" class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">Excel</a>
                </td>
            </tr>
        `).join('');
    })
    .catch(error => {
        document.getElementById('tabla-cotizaciones').innerHTML = '<tr><td colspan="7" class="text-center py-8 text-red-500">Error al cargar cotizaciones</td></tr>';
        console.error(error);
    });
</script>
@endsection