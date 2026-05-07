<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistente Rayo Verde</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <style>
        body { background-color: #fcfdfc; height: 100vh; display: flex; flex-direction: column; }
        .chat-header { background: white; border-bottom: 1px solid rgba(0,0,0,0.05); padding: 15px; }
        #chat-container { flex: 1; overflow-y: auto; padding: 20px; }
        .message { max-width: 80%; margin-bottom: 15px; padding: 12px 18px; border-radius: 20px; font-size: 0.95rem; }
        .bot-msg { background: white; border: 1px solid #e9ecef; align-self: flex-start; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
        .user-msg { background: #27ae60; color: white; align-self: flex-end; margin-left: auto; }
        .chat-input-area { background: white; padding: 20px; border-top: 1px solid rgba(0,0,0,0.05); }
        .input-group { background: #f8f9fa; border-radius: 30px; padding: 5px 15px; }
        .input-group input { border: none; background: transparent; box-shadow: none !important; }
        .btn-send { color: #27ae60; border: none; background: transparent; font-size: 1.2rem; }
    </style>
</head>
<body>

    <div class="chat-header d-flex align-items-center">
        <a href="{{ url('/') }}" class="text-dark me-3 text-decoration-none"><i class="fas fa-arrow-left"></i></a>
        <div class="d-flex align-items-center">
            <div class="bg-success rounded-circle me-2" style="width: 10px; height: 10px;"></div>
            <h6 class="mb-0 fw-bold">Asistente Rayo Verde</h6>
        </div>
    </div>

    <div id="chat-container" class="d-flex flex-column">
        <div class="message bot-msg">
            ¡Hola! Soy el asistente de <b>Rayo Verde</b>. ¿En qué puedo ayudarte hoy?
        </div>
    </div>

    <div class="chat-input-area">
        <form id="chat-form">
            <div class="input-group">
                <input type="text" id="user-input" class="form-control" placeholder="Escribe tu mensaje aquí..." autocomplete="off">
                <button type="submit" class="btn-send"><i class="fas fa-paper-plane"></i></button>
            </div>
            <div id="performance-tag" class="text-muted small mt-2 text-center" style="font-size: 0.7rem;"></div>
        </form>
    </div>

    <script>
    const chatForm = document.getElementById('chat-form');
    const chatContainer = document.getElementById('chat-container');
    const userInput = document.getElementById('user-input');
    const perfTag = document.getElementById('performance-tag');

    // Variable para mantener la sesión del bot
    let currentConversationId = null; 

    chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const text = userInput.value.trim();
    if(!text) return;

    // 1. Renderizar mensaje del usuario
    appendMessage(text, 'user-msg');
    userInput.value = '';

    // 2. MOSTRAR indicador de carga (Escribiendo...)
    // Le ponemos una ID o clase específica para poder borrarlo luego
    const loadingDiv = document.createElement('div');
    loadingDiv.className = 'message bot-msg bot-loading';
    loadingDiv.innerHTML = '<i>Escribiendo...</i>';
    chatContainer.appendChild(loadingDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;

    try {
        // 3. Llamada a la API (Aquí es donde ocurren esos 5-12 segundos)
        const response = await fetch('/api/chatbot/webhook', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                message: text,
                id_conversacion: currentConversationId 
            })
        });

        const data = await response.json();

        // 4. QUITAR indicador de carga antes de mostrar la respuesta real
        loadingDiv.remove(); 

        if (data.id_conversacion) {
            currentConversationId = data.id_conversacion;
        }

        // 5. Renderizar respuesta real del Bot
        appendMessage(data.reply, 'bot-msg');
        
        perfTag.innerHTML = `Respuesta en: <b>${data.timing}s</b> ${data.timing < 3 ? '✅' : '❌'}`;

    } catch (error) {
        // En caso de error, también quitamos el "Escribiendo..."
        loadingDiv.remove(); 
        appendMessage("Lo siento, tuve un problema de conexión.", 'bot-msg');
    }
});

 
function appendMessage(text, className) {
    const div = document.createElement('div');
    div.className = `message ${className}`;
    // Esta línea convierte los \n en saltos de línea reales (BR)
    div.innerHTML = text.replace(/\\n/g, '<br>').replace(/\n/g, '<br>'); 
    chatContainer.appendChild(div);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

</script>
</body>
</html>