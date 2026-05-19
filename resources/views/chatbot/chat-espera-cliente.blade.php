@extends('layouts.user-sidebar')
@section('title', 'Soporte en Vivo')
@section('breadcrumb', 'Atención Humana')

@section('content')
<div class="max-w-4xl mx-auto h-[calc(100vh-180px)] flex flex-col">
    <div class="bg-white rounded-2xl shadow-sm border border-[#d4edda] flex flex-col flex-1 overflow-hidden">
        
        <div class="px-6 py-4 flex items-center gap-3 bg-gradient-to-r from-[#0e2a10] to-[#1e6b2e] text-white">
            <div class="w-10 h-10 rounded-full bg-[#4CAF50]/20 flex items-center justify-center border border-[#4CAF50]/30">
                <i class="fas fa-user-tie text-lg"></i>
            </div>
            <div>
                <h2 class="font-display text-base tracking-wide uppercase">Asesor de Ventas</h2>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 bg-orange-400 rounded-full animate-pulse" id="status-ping"></span>
                    <span class="text-[10px] uppercase tracking-widest text-[#7dcba8]" id="status-text">Esperando Agente...</span>
                </div>
            </div>
        </div>

        <div id="chat-container" class="flex-1 overflow-y-auto p-4 space-y-4 bg-[#f8faf7]"></div>

        <div class="p-4 bg-white border-t border-[#eef4ea]">
            <form id="chat-form" class="relative flex items-center gap-2">
                <input type="text" id="user-input" class="w-full bg-[#f5fbf2] border border-[#c3e6cb] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#4CAF50] focus:ring-4 focus:ring-[#4CAF50]/10 transition-all placeholder-[#7dcba8]" placeholder="Escribe tu mensaje al asesor..." autocomplete="off">
                <button type="submit" class="w-11 h-11 rounded-xl bg-[#4CAF50] text-white flex items-center justify-center shadow-lg hover:bg-[#27ae60] transition-colors">
                    <i class="fas fa-paper-plane text-sm"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .message { max-width: 85%; padding: 12px 16px; border-radius: 18px; font-size: 0.875rem; line-height: 1.5; animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .user-msg { align-self: flex-end; background-color: #4CAF50; color: white; border-bottom-right-radius: 4px; margin-left: auto; }
    .agent-msg { align-self: flex-start; background-color: white; color: #1d2b1a; border: 1px solid #d4edda; border-bottom-left-radius: 4px; margin-right: auto; }
    #chat-container { display: flex; flex-direction: column; }
    #chat-container::-webkit-scrollbar { width: 4px; }
    #chat-container::-webkit-scrollbar-thumb { background: #c3e6cb; border-radius: 10px; }
</style>
@endpush

@push('scripts')
<script>
    const idConversacion = "{{ $conversacion->id_conversacion }}";
    const chatContainer = document.getElementById('chat-container');
    let totalMensajes Mostrados = 0;

    function cargarMensajes() {
        fetch(`/chat/mensajes-cliente/${idConversacion}`)
            .then(res => res.json())
            .then(mensajes => {
                if(mensajes.length !== totalMensajesMostrados) {
                    chatContainer.innerHTML = '';
                    mensajes.forEach(m => {
                        const clase = m.emisor === 'usuario' ? 'user-msg' : 'agent-msg';
                        const div = document.createElement('div');
                        div.className = `message ${clase} mb-3`;
                        div.innerHTML = m.contenido.replace(/\n/g, '<br>');
                        chatContainer.appendChild(div);

                        if(m.emisor === 'agente') {
                            document.getElementById('status-ping').className = "w-2 h-2 bg-green-400 rounded-full";
                            document.getElementById('status-text').innerText = "En Línea con Asesor";
                        }
                    });
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                    totalMensajesMostrados = mensajes.length;
                }
            });
    }

    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('user-input');
        const texto = input.value.trim();
        if(!texto) return;

        fetch(`/chat/enviar-cliente/${idConversacion}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ message: texto })
        }).then(() => {
            input.value = '';
            cargarMensajes();
        });
    });

    setInterval(cargarMensajes, 2000);
    window.onload = cargarMensajes;
</script>
@endpush