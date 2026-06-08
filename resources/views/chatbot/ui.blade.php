@extends('layouts.user-sidebar')
@section('title', 'ChatBot de Soporte')
@section('breadcrumb', 'ChatBot')

@section('content')
<div class="max-w-4xl mx-auto h-[calc(100vh-180px)] flex flex-col">
    <div class="bg-white rounded-2xl shadow-sm border border-[#d4edda] flex flex-col flex-1 overflow-hidden">
        
        <div class="px-6 py-4 flex items-center gap-3 bg-gradient-to-r from-[#0e2a10] to-[#1e6b2e] text-white">
            <div class="w-10 h-10 rounded-full bg-[#4CAF50]/20 flex items-center justify-center border border-[#4CAF50]/30">
                <i id="header-icon" class="fas fa-robot text-lg"></i>
            </div>
            <div>
                <h2 id="header-title" class="font-display text-base tracking-wide">ASISTENTE VIRTUAL</h2>
                <div class="flex items-center gap-1.5">
                    <span id="status-ping" class="w-2 h-2 bg-[#7BE07B] rounded-full animate-pulse"></span>
                    <span id="status-text" class="text-[10px] uppercase tracking-widest text-[#7dcba8]">En línea</span>
                </div>
            </div>
        </div>

        <div id="chat-container" class="flex-1 overflow-y-auto p-4 space-y-4 bg-[#f8faf7]"></div>

        <div class="p-4 bg-white border-t border-[#eef4ea]">
            <form id="chat-form" class="relative flex items-center gap-2">
                <input type="text" id="user-input" 
                    class="w-full bg-[#f5fbf2] border border-[#c3e6cb] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#4CAF50] focus:ring-4 focus:ring-[#4CAF50]/10 transition-all placeholder-[#7dcba8]"
                    placeholder="Escribe tu mensaje aquí..." autocomplete="off">
                
                <button type="submit" class="btn-send w-11 h-11 rounded-xl bg-[#4CAF50] text-white flex items-center justify-center shadow-lg shadow-[#4CAF50]/20 hover:bg-[#27ae60] transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-paper-plane text-sm"></i>
                </button>
            </form>
            <p class="text-[10px] text-center text-[#3a6040] mt-2 opacity-60">
                Al usar el chat, aceptas el procesamiento automático de tus datos para cotizaciones.
            </p>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .message { max-width: 85%; padding: 12px 16px; border-radius: 18px; font-size: 0.875rem; line-height: 1.5; position: relative; animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .user-msg { align-self: flex-end; background-color: #4CAF50; color: white; border-bottom-right-radius: 4px; margin-left: auto; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2); }
    .bot-msg { align-self: flex-start; background-color: white; color: #1d2b1a; border: 1px solid #d4edda; border-bottom-left-radius: 4px; margin-right: auto; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03); }
    .agent-msg { align-self: flex-start; background-color: #fffde7; color: #5d4037; border: 1px solid #fff59d; border-bottom-left-radius: 4px; margin-right: auto; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03); }
    #chat-container::-webkit-scrollbar { width: 4px; }
    #chat-container::-webkit-scrollbar-track { background: transparent; }
    #chat-container::-webkit-scrollbar-thumb { background: #c3e6cb; border-radius: 10px; }
</style>
@endpush

@push('scripts')
<script>
    const chatForm = document.getElementById('chat-form');
    const chatContainer = document.getElementById('chat-container');
    const userInput = document.getElementById('user-input');
    const sendBtn = document.querySelector('.btn-send');
    const headerTitle = document.getElementById('header-title');
    const headerIcon = document.getElementById('header-icon');
    const statusText = document.getElementById('status-text');
    const statusPing = document.getElementById('status-ping');

    let currentConversationId = null;
    let isProcessing = false;
    let isAgentMode = false;
    let pollingInterval = null;
    let totalMensajes = 0;

    async function sendMessageToBot(text) {
        if (!text || isProcessing) return;
        isProcessing = true;
        userInput.disabled = true;
        sendBtn.disabled = true;
        userInput.placeholder = "Esperando respuesta...";

        if (text !== 'init_bot') {
            appendMessage(text, 'user-msg');
        }

        const loadingId = showLoading();

        try {
            const response = await fetch('{{ route("chatbot.webhook") }}', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({ 
                    message: (text === 'init_bot' ? 'hola' : text), 
                    id_conversacion: currentConversationId 
                })
            });

            const data = await response.json();
            removeLoading(loadingId);

            if (data.id_conversacion) currentConversationId = data.id_conversacion;
            if (data.reply) appendMessage(data.reply, 'bot-msg');

            if (data.redirect) {
                activarModoAgente();
            }
        } catch (e) {
            removeLoading(loadingId);
            appendMessage("Error de conexión.", 'bot-msg');
        } finally {
            isProcessing = false;
            userInput.disabled = false;
            sendBtn.disabled = false;
            userInput.placeholder = "Escribe aquí...";
            userInput.focus();
        }
    }

async function sendMessageToAgent(text) {
    if (!text || !currentConversationId) return;

    appendMessage(text, 'user-msg');
    
    try {
        await fetch(`/chat/enviar-cliente/${currentConversationId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: text })
        });
    } catch (e) {
        console.error("Error al enviar", e);
    }
}

    function activarModoAgente() {
        isAgentMode = true;
        headerTitle.innerText = "ASESOR DE VENTAS";
        headerIcon.className = "fas fa-user-tie text-lg";
        statusText.innerText = "Esperando Agente...";
        statusPing.className = "w-2 h-2 bg-orange-400 rounded-full animate-pulse";

        if (!pollingInterval) {
            pollingInterval = setInterval(cargarMensajesAgente, 2000);
        }
    }

    function desactivarModoAgente() {
        isAgentMode = false;
        headerTitle.innerText = "ASISTENTE VIRTUAL";
        headerIcon.className = "fas fa-robot text-lg";
        statusText.innerText = "En línea";
        statusPing.className = "w-2 h-2 bg-[#7BE07B] rounded-full animate-pulse";

        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
        
        totalMensajes = 0; 
    }

    function cargarMensajesAgente() {
        if (!currentConversationId) return;

        fetch(`/chat/mensajes-cliente/${currentConversationId}`)
            .then(res => res.json())
            .then(mensajes => {
                if (mensajes.length !== totalMensajes) {
                    chatContainer.innerHTML = '';
                    let finalizado = false;

                    mensajes.forEach(m => {
                        if (m.emisor === 'sistema') {
                            finalizado = true;
                            appendMessage(m.contenido, 'bot-msg');
                        } else {
                            const clase = m.emisor === 'usuario' ? 'user-msg' : (m.emisor === 'agente' ? 'agent-msg' : 'bot-msg');
                            appendMessage(m.contenido, clase);
                        }

                        if (m.emisor === 'agente') {
                            statusText.innerText = "En Línea con Asesor";
                            statusPing.className = "w-2 h-2 bg-green-400 rounded-full";
                        }
                    });

                    totalMensajes = mensajes.length;

                    if (finalizado) {
                        desactivarModoAgente();
                    }
                }
            });
    }

    function appendMessage(text, className) {
        const div = document.createElement('div');
        div.className = `message ${className}`;
        div.innerHTML = text.replace(/\\n/g, '\n').replace(/\n/g, '<br>');
        chatContainer.appendChild(div);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function showLoading() {
        const id = 'loading-' + Date.now();
        const loading = document.createElement('div');
        loading.id = id;
        loading.className = 'message bot-msg italic flex items-center gap-2';
        loading.innerHTML = '<i class="fas fa-circle-notch fa-spin text-[#4CAF50]"></i> <span class="text-[#7dcba8]">Escribiendo...</span>';
        chatContainer.appendChild(loading);
        chatContainer.scrollTop = chatContainer.scrollHeight;
        return id;
    }

    function removeLoading(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const text = userInput.value.trim();
        if (text) {
            if (isAgentMode) {
                sendMessageToAgent(text);
            } else {
                sendMessageToBot(text);
            }
            userInput.value = '';
        }
    });

    window.onload = () => sendMessageToBot('init_bot');
</script>
@endpush