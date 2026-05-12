<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistente Rayo Verde</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #fcfdfc; height: 100vh; display: flex; flex-direction: column; margin: 0; font-family: sans-serif; }
        .chat-header { background: white; border-bottom: 1px solid rgba(0,0,0,0.05); padding: 15px; position: sticky; top: 0; z-index: 100; }
        #chat-container { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; }
        .message { max-width: 80%; margin-bottom: 15px; padding: 12px 18px; border-radius: 20px; font-size: 0.95rem; line-height: 1.4; }
        .bot-msg { background: white; border: 1px solid #e9ecef; align-self: flex-start; box-shadow: 0 2px 5px rgba(0,0,0,0.02); color: #333; }
        .user-msg { background: #27ae60; color: white; align-self: flex-end; }
        .chat-input-area { background: white; padding: 20px; border-top: 1px solid rgba(0,0,0,0.05); }
        .input-group { background: #f8f9fa; border-radius: 30px; padding: 5px 15px; border: 1px solid #eee; }
        .input-group input { border: none; background: transparent; box-shadow: none !important; }
        .btn-send { color: #27ae60; border: none; background: transparent; font-size: 1.2rem; cursor: pointer; transition: transform 0.2s; }
        .btn-send:hover { transform: scale(1.1); }
        i { font-style: normal; }
    </style>
</head>
<body>
    <div class="chat-header d-flex align-items-center">
        <a href="{{ url('/') }}" class="text-dark me-3 text-decoration-none"><i class="fas fa-arrow-left"></i></a>
        <h6 class="mb-0 fw-bold">Asistente Rayo Verde</h6>
    </div>

    <div id="chat-container"></div>

    <div class="chat-input-area">
        <form id="chat-form">
            <div class="input-group">
                <input type="text" id="user-input" class="form-control" placeholder="Escribe aquí..." autocomplete="off">
                <button type="submit" class="btn-send"><i class="fas fa-paper-plane"></i></button>
            </div>
        </form>
    </div>

    <script>
        const chatForm = document.getElementById('chat-form');
        const chatContainer = document.getElementById('chat-container');
        const userInput = document.getElementById('user-input');
        
        // Mantener el ID de conversación en la sesión del navegador
        let currentConversationId = null;

        async function sendMessage(text) {
            if (!text) return;

            // Mostrar el mensaje del usuario (excepto el disparador inicial)
            if (text !== 'init_bot') {
                appendMessage(text, 'user-msg');
            }

            // Indicador de carga
            const loading = document.createElement('div');
            loading.className = 'message bot-msg';
            loading.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Pensando...';
            chatContainer.appendChild(loading);
            chatContainer.scrollTop = chatContainer.scrollHeight;

            try {
                const response = await fetch('{{ route("chatbot.webhook") }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // CRÍTICO: Token de seguridad para Laravel
                    },
                    body: JSON.stringify({ 
                        message: (text === 'init_bot' ? 'hola' : text), 
                        id_conversacion: currentConversationId 
                    })
                });

                const data = await response.json();
                loading.remove();

                if (data.id_conversacion) {
                    currentConversationId = data.id_conversacion;
                }

                if (data.reply) {
                    appendMessage(data.reply, 'bot-msg');
                }

                // Manejo de redirección si el bot finaliza la cotización
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2500);
                }

            } catch (e) {
                loading.remove();
                appendMessage("Lo siento, tuve un problema de conexión. Inténtalo de nuevo.", 'bot-msg');
                console.error("Chatbot Error:", e);
            }
        }

        chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const text = userInput.value.trim();
            if (text) {
                sendMessage(text);
                userInput.value = '';
            }
        });

  function appendMessage(text, className) {
    const div = document.createElement('div');
    div.className = `message ${className}`;
    
    // Corregido: Primero limpia posibles escapes dobles y luego convierte a <br>
    let formattedText = text.replace(/\\n/g, '\n').replace(/\n/g, '<br>');
    
    div.innerHTML = formattedText;
    chatContainer.appendChild(div);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}
        
        // Iniciar el chat automáticamente al cargar
        window.onload = () => {
            sendMessage('init_bot');
        };
    </script>
</body>
</html>