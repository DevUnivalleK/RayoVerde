@extends('PersonalVentas.app')
@section('title', 'Atención de Chat')
@section('breadcrumb', 'Consola de Soporte')

@section('content')
<div class="max-w-4xl mx-auto h-[calc(100vh-180px)] flex flex-col">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col flex-1 overflow-hidden">
        
        <div class="px-6 py-4 flex items-center gap-3 bg-emerald-900 text-white">
            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                <i class="fas fa-user text-lg text-green-300"></i>
            </div>
            <div>
                <h2 class="font-bold text-base tracking-wide uppercase">Atendiendo a: {{ $conversacion->cliente_nombre }}</h2>
                <span class="text-[10px] bg-emerald-800 px-2 py-0.5 rounded text-green-200 font-mono">ID Conversación: #{{ $conversacion->id_conversacion }}</span>
            </div>
        </div>

        <div id="chat-container" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50"></div>

        <div class="p-4 bg-white border-t">
            <form id="chat-form" class="relative flex items-center gap-2">
                <input type="text" id="user-input" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-200 transition-all" placeholder="Escribe una respuesta formal para el cliente..." autocomplete="off">
                <button type="submit" class="w-11 h-11 rounded-xl bg-emerald-800 text-white flex items-center justify-center shadow-md hover:bg-emerald-900 transition-colors">
                    <i class="fas fa-paper-plane text-sm"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .message { max-width: 80%; padding: 10px 14px; border-radius: 14px; font-size: 0.85rem; line-height: 1.5; animation: fadeIn 0.2s ease-out; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .user-msg { align-self: flex-start; background-color: #dfeffd; color: #1e3a8a; border: 1px solid #bfdbfe; border-bottom-left-radius: 2px; margin-right: auto; }
    .agent-msg { align-self: flex-end; background-color: #065f46; color: white; border-bottom-right-radius: 2px; margin-left: auto; }
    /* Globos especiales para identificar qué habló el bot previamente */
    .bot-context-msg { align-self: flex-start; background-color: #fffde7; color: #5d4037; border: 1px dashed #fff59d; border-bottom-left-radius: 2px; margin-right: auto; font-style: italic; }
    #chat-container { display: flex; flex-direction: column; }
    #chat-container::-webkit-scrollbar { width: 4px; }
    #chat-container::-webkit-scrollbar-thumb { background: #b0bec5; border-radius: 10px; }
</style>
@endpush

@push('scripts')
<script>
    const idConversacion = "{{ $conversacion->id_conversacion }}";
    const chatContainer = document.getElementById('chat-container');
    let totalMensajes = 0;

    function cargarMensajes() {
        // Corregido el endpoint para que llame exactamente a tu ruta 'mensajes-agente'
        fetch(`/ventas/chat/mensajes-agente/${idConversacion}`)
            .then(res => res.json())
            .then(res => {
                // Adaptado para leer 'res.data' enviado desde tu ChatVentasController
                if (res.success && res.data.length !== totalMensajes) {
                    chatContainer.innerHTML = '';
                    
                    res.data.forEach(m => {
                        let clase = 'bot-context-msg';
                        let prefijo = '🤖 <b>Bot:</b> ';
                        
                        // Mapeo según el emisor de tu base de datos
                        if (m.emisor === 'usuario') { 
                            clase = 'user-msg'; 
                            prefijo = '👤 <b>Cliente:</b> '; 
                        }
                        if (m.emisor === 'agente') { 
                            clase = 'agent-msg'; 
                            prefijo = '💼 '; 
                        }

                        const div = document.createElement('div');
                        div.className = `message ${clase} mb-2`;
                        div.innerHTML = prefijo + m.contenido.replace(/\n/g, '<br>');
                        chatContainer.appendChild(div);
                    });
                    
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                    totalMensajes = res.data.length;
                }
            })
            .catch(err => console.error("Error al cargar los mensajes:", err));
    }

    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('user-input');
        const texto = input.value.trim();
        if(!texto) return;

        fetch(`/ventas/chat/enviar-agente/${idConversacion}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ contenido: texto }) // Sincronizado el campo con el Request de tu controlador
        }).then(() => {
            input.value = '';
            cargarMensajes();
        });
    });

    setInterval(cargarMensajes, 2000);
    window.onload = cargarMensajes;
</script>
@endpush