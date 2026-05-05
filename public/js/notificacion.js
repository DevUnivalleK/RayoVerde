// Esperar a que el DOM esté cargado para evitar errores de "null"
document.addEventListener('DOMContentLoaded', () => {
    // Captura de elementos
    const btnAbrir = document.getElementById('btnAbrirNotificaciones');
    const panelIndex = document.getElementById('notif-panel-index');
    const notifCount = document.getElementById('notif-count-index');

    // 1. Lógica para Abrir / Cerrar
    if (btnAbrir && panelIndex) {
        btnAbrir.addEventListener('click', (e) => {
            e.preventDefault(); 
            e.stopPropagation(); // Evita que el evento se propague al document inmediatamente

            // Verificamos el estado actual computado para evitar fallos en el primer clic
            const isVisible = window.getComputedStyle(panelIndex).display === 'block';
            
            if (isVisible) {
                panelIndex.style.display = 'none';
            } else {
                panelIndex.style.display = 'block';
            }
        });
    }

    // 2. Cerrar panel si se hace clic fuera del mismo
    document.addEventListener('click', (e) => {
        if (panelIndex && btnAbrir) {
            // Si el panel está abierto y el clic NO fue dentro del panel ni en el botón[cite: 2]
            if (!panelIndex.contains(e.target) && !btnAbrir.contains(e.target)) {
                panelIndex.style.display = 'none';
            }
        }
    });
});

/** 
 * FUNCIONES GLOBALES 
 * (Se dejan fuera para que los onclick="xxx" del HTML funcionen)[cite: 2]
 */

// Función para cerrar (Botón X)[cite: 2]
function closeNotifPanelIndex() {
    const panel = document.getElementById('notif-panel-index');
    if (panel) {
        panel.style.display = 'none';
    }
}

// Función: Marcar todo como leído[cite: 2]
function markAllReadIndex() {
    const unreadItems = document.querySelectorAll('.notif-item-index.unread');
    const notifCount = document.getElementById('notif-count-index');

    unreadItems.forEach(item => {
        item.classList.remove('unread');
    });
    
    // Ocultar el badge rojo con una transición suave[cite: 2]
    if (notifCount) {
        notifCount.style.transition = "opacity 0.3s ease";
        notifCount.style.opacity = "0";
        setTimeout(() => {
            notifCount.style.display = 'none';
        }, 300);
    }
}