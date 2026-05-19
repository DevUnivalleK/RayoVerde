@extends('PersonalVentas.app')

@section('title', 'Gestión de Cotizaciones')
@section('breadcrumb', 'Cotizaciones')

@section('content')
<div class="bg-white rounded shadow overflow-hidden">
    <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-xl font-bold text-green-700">Cotizaciones del Sistema</h2>
        <span id="notificacion-estado" class="hidden px-3 py-1 text-sm rounded bg-green-100 text-green-800 font-medium"></span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                    <th class="px-4 py-3">Código</th>
                    <th class="px-4 py-3">Cliente</th>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3 text-center">Modificar Estado</th>
                </tr>
            </thead>
            <tbody id="tabla-cotizaciones" class="divide-y">
                <tr><td colspan="6" class="text-center py-8 text-gray-500">Cargando cotizaciones...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
// Mapeo dinámico de colores según el ID del estado real
function getEstadoClass(idEstado) {
    const colores = {
        1: 'bg-yellow-100 text-yellow-800', // Primer estado (ej. Pendiente)
        2: 'bg-green-100 text-green-800',   // Segundo estado (ej. Admitido)
        3: 'bg-red-100 text-red-800'         // Tercer estado (ej. Rechazado)
    };
    return colores[idEstado] || 'bg-gray-100 text-gray-800';
}

function cargarCotizaciones() {
    fetch('/ventas/cotizaciones/data')
        .then(response => response.json())
        .then(res => {
            const tbody = document.getElementById('tabla-cotizaciones');
            const lista = res.data;
            const estadosSistema = res.estados_sistema; // 🟢 Tus 3 estados de la BD
            
            if (!lista || lista.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-gray-500">No hay cotizaciones registradas en el sistema</td></tr>';
                return;
            }
            
            tbody.innerHTML = lista.map(c => {
                const nombreCliente = c.usuario ? `${c.usuario.nombre || ''} ${c.usuario.apellido || ''}`.trim() : 'Cliente General';
                const textoEstadoReal = c.estado ? c.estado.nombre : 'Sin Estado';
                
                // 🟢 Generamos los <option> del select recorriendo únicamente los estados reales de tu BD
                const optionsHTML = estadosSistema.map(est => `
                    <option value="${est.id_estado}" ${c.id_estado == est.id_estado ? 'selected' : ''}>
                        ${est.nombre}
                    </option>
                `).join('');
                
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-mono font-bold">${c.codigo}</td>
                        <td class="px-4 py-3 text-sm">${nombreCliente}</td>
                        <td class="px-4 py-3 text-sm">${new Date(c.generado_en).toLocaleDateString()}</td>
                        <td class="px-4 py-3 text-sm font-bold text-green-700">Bs ${c.total}</td>
                        <td class="px-4 py-3">
                            <span id="badge-${c.id_cotizacion}" class="px-2 py-1 text-xs rounded-full ${getEstadoClass(c.id_estado)}">
                                ${textoEstadoReal}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <select onchange="cambiarEstado(${c.id_cotizacion}, this)" class="border border-gray-300 rounded px-2 py-1 bg-white text-xs">
                                ${optionsHTML}
                            </select>
                        </td>
                    </tr>
                `;
            }).join('');
        })
        .catch(error => {
            document.getElementById('tabla-cotizaciones').innerHTML = '<tr><td colspan="6" class="text-center py-8 text-red-500">Error al cargar datos</td></tr>';
            console.error(error);
        });
}

function cambiarEstado(idCotizacion, selectElement) {
    const nuevoEstadoId = selectElement.value;
    const textoEstadoSeleccionado = selectElement.options[selectElement.selectedIndex].text;
    const notificacion = document.getElementById('notificacion-estado');
    
    fetch(`/ventas/cotizaciones/${idCotizacion}/actualizar-estado`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ id_estado: nuevoEstadoId })
    })
    .then(response => response.json())
    .then(res => {
        if (res.success) {
            const badge = document.getElementById(`badge-${idCotizacion}`);
            badge.className = `px-2 py-1 text-xs rounded-full ${getEstadoClass(nuevoEstadoId)}`;
            badge.innerText = textoEstadoSeleccionado;
            
            notificacion.innerText = "Estado actualizado con éxito";
            notificacion.classList.remove('hidden');
            setTimeout(() => notificacion.classList.add('hidden'), 3000);
        } else {
            alert('No se pudo actualizar el estado');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error en el servidor');
    });
}

document.addEventListener('DOMContentLoaded', cargarCotizaciones);
</script>
@endsection