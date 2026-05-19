@extends('PersonalVentas.app')

@section('title', 'Bandeja de Chats')
@section('breadcrumb', 'Chats Pendientes')

@section('content')
<div class="bg-white rounded shadow overflow-hidden">
    <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-xl font-bold text-green-700">Solicitudes de Chat Pendientes</h2>
        <span class="text-xs text-gray-500 uppercase tracking-wider">Derivaciones del Asistente</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b">
                <tr class="text-xs font-medium text-gray-500 uppercase">
                    <th class="px-6 py-3">Cliente</th>
                    <th class="px-6 py-3">Contacto</th>
                    <th class="px-6 py-3">Fecha de Inicio</th>
                    <th class="px-6 py-3">Estado Sistema</th>
                    <th class="px-6 py-3 text-center">Acción</th>
                </tr>
            </thead>
            <tbody id="tabla-chats" class="divide-y text-sm">
                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-500">Cargando chats en espera...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
function cargarBandejaChats() {
    fetch('/ventas/chat/derivaciones')
        .then(response => response.json())
        .then(res => {
            const tbody = document.getElementById('tabla-chats');
            const lista = res.data;

            if (!lista || lista.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-gray-500 font-medium">No hay solicitudes de chat pendientes por el momento.</td></tr>';
                return;
            }

            tbody.innerHTML = lista.map(chat => {
                const fechaSolicitud = new Date(chat.iniciada_en).toLocaleString();
                
                return `
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-800">${chat.cliente_nombre}</td>
                        <td class="px-6 py-4 text-gray-600">${chat.cliente_correo}</td>
                        <td class="px-6 py-4 text-gray-500">${fechaSolicitud}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 animate-pulse">
                                ${chat.estado}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button onclick="tomarChat(${chat.id_conversacion})" class="bg-emerald-800 hover:bg-emerald-900 text-white px-4 py-1.5 rounded text-xs font-bold transition-all shadow-sm">
                                Atender Chat
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        })
        .catch(error => {
            document.getElementById('tabla-chats').innerHTML = '<tr><td colspan="5" class="text-center py-8 text-red-500 font-semibold">Error al conectar con el servidor de mensajería.</td></tr>';
            console.error(error);
        });
}

function tomarChat(idConversacion) {
    window.location.href = `/ventas/chat/atender/${idConversacion}`;
}

document.addEventListener('DOMContentLoaded', () => {
    cargarBandejaChats();
    
    setInterval(cargarBandejaChats, 5000);
});
</script>
@endsection