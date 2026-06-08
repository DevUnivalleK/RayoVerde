@extends('PersonalVentas.app')
@section('title', 'Atención de Chat')
@section('breadcrumb', 'Consola de Soporte')

@section('content')
<div class="max-w-4xl mx-auto h-[calc(100vh-180px)] flex flex-col">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col flex-1 overflow-hidden">
        
        <div class="px-6 py-4 flex items-center justify-between bg-emerald-900 text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                    <i class="fas fa-user text-lg text-green-300"></i>
                </div>
                <div>
                    <h2 class="font-bold text-base tracking-wide uppercase">Atendiendo a: {{ $conversacion->cliente_nombre }}</h2>
                    <span class="text-[10px] bg-emerald-800 px-2 py-0.5 rounded text-green-200 font-mono">ID: #{{ $conversacion->id_conversacion }}</span>
                </div>
            </div>
            
            <button onclick="finalizarAtencion()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-bold transition-all shadow-sm flex items-center gap-2">
                <i class="fas fa-sign-out-alt"></i> Finalizar Derivación
            </button>
        </div>

        <div id="chat-container" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50"></div>

        <div class="p-4 bg-white border-t">
            <form id="chat-form" class="relative flex items-center gap-2">
                <input type="text" id="user-input" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-200 transition-all" placeholder="Escribe tu respuesta..." autocomplete="off">
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
    .message { max-width: 80%; padding: 10px 14px; border-radius: 14px; font-size: 0.85rem; line-height: 1.5; }
    .user-msg { align-self: flex-start; background-color: #dfeffd; color: #1e3a8a; border: 1px solid #bfdbfe; margin-right: auto; }
    .agent-msg { align-self: flex-end; background-color: #065f46; color: white; margin-left: auto; }
    .bot-context-msg { align-self: flex-start; background-color: #fffde7; border: 1px dashed #fff59d; color: #5d4037; margin-right: auto; font-style: italic; }
    #chat-container { display: flex; flex-direction: column; }
</style>
@endpush

@push('scripts')
<script>
    const idConversacion = "{{ $conversacion->id_conversacion }}";
    const chatContainer = document.getElementById('chat-container');
    let totalMensajes = 0;

    function cargarMensajes() {
        fetch("{{ route('ventas.chat.mensajes.agente', $conversacion->id_conversacion) }}")
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data.length !== totalMensajes) {
                    chatContainer.innerHTML = '';
                    res.data.forEach(m => {
                        let clase = 'bot-context-msg';
                        let prefijo = '🤖 ';
                        if (m.emisor === 'usuario') { clase = 'user-msg'; prefijo = '👤 '; }
                        if (m.emisor === 'agente') { clase = 'agent-msg'; prefijo = '💼 '; }

                        const div = document.createElement('div');
                        div.className = `message ${clase} mb-2`;
                        div.innerHTML = prefijo + m.contenido.replace(/\n/g, '<br>');
                        chatContainer.appendChild(div);
                    });
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                    totalMensajes = res.data.length;
                }
            });
    }

    function finalizarAtencion() {
        if(confirm('¿Finalizar atención? El cliente volverá al ChatBot.')) {
            fetch("{{ route('ventas.chat.finalizar', $conversacion->id_conversacion) }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(() => {
                window.location.href = "{{ route('ventas.chat.bandeja') }}";
            });
        }
    }

    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('user-input');
        fetch("{{ route('ventas.chat.enviar.agente', $conversacion->id_conversacion) }}", {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            },
            body: JSON.stringify({ contenido: input.value })
        }).then(() => {
            input.value = '';
            cargarMensajes();
        });
    });

    setInterval(cargarMensajes, 2000);
    window.onload = cargarMensajes;
</script>
@endpush