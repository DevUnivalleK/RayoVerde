@extends('layouts.user-sidebar')
@section('title', 'ChatBot de Soporte')
@section('breadcrumb', 'ChatBot')

@section('content')
<div class="max-w-4xl mx-auto h-[calc(100vh-180px)] flex flex-col">
    <div class="bg-white rounded-2xl shadow-sm border border-[#d4edda] flex flex-col flex-1 overflow-hidden">
        
        <div class="px-6 py-4 flex items-center gap-3 bg-gradient-to-r from-[#0e2a10] to-[#1e6b2e] text-white">
            <div class="w-10 h-10 rounded-full bg-[#4CAF50]/20 flex items-center justify-center border border-[#4CAF50]/30">
                <i class="fas fa-robot text-lg"></i>
            </div>
            <div>
                <h2 class="font-display text-base tracking-wide">ASISTENTE VIRTUAL</h2>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 bg-[#7BE07B] rounded-full animate-pulse"></span>
                    <span class="text-[10px] uppercase tracking-widest text-[#7dcba8]">En línea</span>
                </div>
            </div>
        </div>

        <div id="chat-container" class="flex-1 overflow-y-auto p-4 space-y-4 bg-[#f8faf7]">
            </div>

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
    /* Estilos de los globos de texto */
    .message {
        max-width: 85%;
        padding: 12px 16px;
        border-radius: 18px;
        font-size: 0.875rem;
        line-height: 1.5;
        position: relative;
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .user-msg {
        align-self: flex-end;
        background-color: #4CAF50;
        color: white;
        border-bottom-right-radius: 4px;
        margin-left: auto;
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);
    }

    .bot-msg {
        align-self: flex-start;
        background-color: white;
        color: #1d2b1a;
        border: 1px solid #d4edda;
        border-bottom-left-radius: 4px;
        margin-right: auto;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    /* Scrollbar personalizado para el chat */
    #chat-container::-webkit-scrollbar { width: 4px; }
    #chat-container::-webkit-scrollbar-track { background: transparent; }
    #chat-container::-webkit-scrollbar-thumb { background: #c3e6cb; border-radius: 10px; }
</style>
@endpush

@push('scripts')
<script>
    // Tu script corregido anteriormente encaja aquí perfectamente
    // Solo asegúrate de que la función appendMessage use clases de Tailwind si es necesario, 
    // pero con el CSS de arriba ya tienes el diseño de las burbujas.
    const chatForm = document.getElementById('chat-form');
    const chatContainer = document.getElementById('chat-container');
    const userInput = document.getElementById('user-input');
    const sendBtn = document.querySelector('.btn-send');
    let currentConversationId = null;
    let isProcessing = false;

    async function sendMessage(text) {
        if (!text || isProcessing) return;
        isProcessing = true;
        userInput.disabled = true;
        sendBtn.disabled = true;
        userInput.placeholder = "Esperando respuesta...";

        if (text !== 'init_bot') {
            appendMessage(text, 'user-msg');
        }

        const loading = document.createElement('div');
        loading.className = 'message bot-msg italic flex items-center gap-2';
        loading.innerHTML = '<i class="fas fa-circle-notch fa-spin text-[#4CAF50]"></i> <span class="text-[#7dcba8]">Escribiendo...</span>';
        chatContainer.appendChild(loading);
        chatContainer.scrollTop = chatContainer.scrollHeight;

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
            loading.remove();

            if (data.id_conversacion) currentConversationId = data.id_conversacion;
            if (data.reply) appendMessage(data.reply, 'bot-msg');

            if (data.redirect) {
                setTimeout(() => { window.location.href = data.redirect; }, 5000);
            }
        } catch (e) {
            loading.remove();
            appendMessage("Lo siento, hubo un error de conexión.", 'bot-msg');
        } finally {
            isProcessing = false;
            userInput.disabled = false;
            sendBtn.disabled = false;
            userInput.placeholder = "Escribe aquí...";
            userInput.focus();
        }
    }

    function appendMessage(text, className) {
        const div = document.createElement('div');
        div.className = `message ${className}`;
        let formattedText = text.replace(/\\n/g, '\n').replace(/\n/g, '<br>');
        div.innerHTML = formattedText;
        chatContainer.appendChild(div);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const text = userInput.value.trim();
        if (text) {
            sendMessage(text);
            userInput.value = '';
        }
    });

    window.onload = () => sendMessage('init_bot');
</script>
@endpush