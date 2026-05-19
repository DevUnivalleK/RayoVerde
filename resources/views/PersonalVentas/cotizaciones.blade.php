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

<div id="modal-detalle" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4 animate-fadeIn">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">
        
        <div class="px-6 py-4 bg-emerald-900 text-white flex justify-between items-center">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <i class="fas fa-file-invoice-dollar text-green-400"></i> Desglose de Productos Requeridos
            </h3>
            <button onclick="cerrarModal()" class="text-white/70 hover:text-white text-xl focus:outline-none">&times;</button>
        </div>

        <div class="p-6 overflow-y-auto space-y-6">
            <div class="overflow-x-auto border border-gray-200 rounded-xl">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left">Producto</th>
                            <th class="px-4 py-3 text-center">Volumen (L)</th>
                            <th class="px-4 py-3 text-right">P. Unitario</th>
                            <th class="px-4 py-3 text-center">Desc. (%)</th>
                            <th class="px-4 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="modal-tabla-items" class="divide-y divide-gray-100 text-gray-700">
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-sm text-gray-600 flex justify-end">
                <div class="space-y-2 text-right max-w-xs w-full">
                    <p><span class="font-medium">Descuento Comercial Directo:</span> <span id="meta-descuento-aplicado" class="font-mono text-red-600 font-medium"></span></p>
                    <p class="text-base font-bold text-emerald-800 pt-1 border-t border-dashed border-gray-300">Total Cotizado: <span id="meta-total-general" class="font-mono"></span></p>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-100 border-t flex justify-end">
            <button onclick="cerrarModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm font-medium transition-colors">
                Cerrar Detalle
            </button>
        </div>
    </div>
</div>

<script>
function getEstadoClass(idEstado) {
    const colores = {
        1: 'bg-yellow-100 text-yellow-800', 
        2: 'bg-green-100 text-green-800',   
        3: 'bg-red-100 text-red-800'         
    };
    return colores[idEstado] || 'bg-gray-100 text-gray-800';
}

function cargarCotizaciones() {
    fetch('/ventas/cotizaciones/data')
        .then(response => response.json())
        .then(res => {
            const tbody = document.getElementById('tabla-cotizaciones');
            const lista = res.data;
            const estadosSistema = res.estados_sistema; 
            
            if (!lista || lista.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-gray-500">No hay cotizaciones registradas en el sistema</td></tr>';
                return;
            }
            
            tbody.innerHTML = lista.map(c => {
                const nombreCliente = c.usuario ? `${c.usuario.nombre || ''} ${c.usuario.apellido || ''}`.trim() : 'Cliente General';
                const textoEstadoReal = c.estado ? c.estado.nombre : 'Sin Estado';
                
                const optionsHTML = estadosSistema.map(est => `
                    <option value="${est.id_estado}" ${c.id_estado == est.id_estado ? 'selected' : ''}>
                        ${est.nombre}
                    </option>
                `).join('');
                
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">
                            <button onclick="verDetalleCotizacion(${c.id_cotizacion}, '${c.codigo}')" class="text-emerald-700 font-mono font-bold hover:text-emerald-950 hover:underline focus:outline-none flex items-center gap-1">
                                <i class="fas fa-search text-[10px] opacity-50"></i> ${c.codigo}
                            </button>
                        </td>
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
            document.getElementById('tabla-tabla-cotizaciones').innerHTML = '<tr><td colspan="6" class="text-center py-8 text-red-500">Error al cargar datos</td></tr>';
            console.error(error);
        });
}

function verDetalleCotizacion(idCotizacion, codigo) {
    const modal = document.getElementById('modal-detalle');
    const tablaItems = document.getElementById('modal-tabla-items');
    
    tablaItems.innerHTML = '<tr><td colspan="5" class="text-center py-6 text-gray-500"><i class="fas fa-spinner animate-spin"></i> Cargando especificaciones...</td></tr>';
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    fetch(`/ventas/cotizaciones/${idCotizacion}/detalle`)
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                if (!res.detalles || res.detalles.length === 0) {
                    tablaItems.innerHTML = '<tr><td colspan="5" class="text-center py-6 text-gray-500">Esta cotización no registra ítems individuales.</td></tr>';
                } else {
                    tablaItems.innerHTML = res.detalles.map(d => {
                        const nombreProd = d.producto ? d.producto.nombre : `Producto #${d.id_producto}`;
                        return `
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">${nombreProd}</td>
                                <td class="px-4 py-3 text-center font-mono">${d.volumen_litros}</td>
                                <td class="px-4 py-3 text-right font-mono">Bs ${d.precio_unitario}</td>
                                <td class="px-4 py-3 text-center text-red-600 font-mono">${parseFloat(d.descuento_pct)}%</td>
                                <td class="px-4 py-3 text-right font-mono font-semibold text-gray-900">Bs ${d.subtotal}</td>
                            </tr>
                        `;
                    }).join('');
                }

                const c = res.cotizacion;
                document.getElementById('meta-descuento-aplicado').innerText = `- Bs ${c.descuento_applied || c.descuento_aplicado || '0.00'}`;
                document.getElementById('meta-total-general').innerText = `Bs ${c.total}`;
            } else {
                tablaItems.innerHTML = '<tr><td colspan="5" class="text-center py-6 text-red-500">No se pudo recuperar el desglose.</td></tr>';
            }
        })
        .catch(err => {
            console.error(err);
            tablaItems.innerHTML = '<tr><td colspan="5" class="text-center py-6 text-red-500">Error de comunicación con el servidor.</td></tr>';
        });
}

function cerrarModal() {
    const modal = document.getElementById('modal-detalle');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
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