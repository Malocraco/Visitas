// Chat en tiempo real - Solución definitiva sin duplicación
class ChatManager {
    constructor(roomId, userId) {
        this.roomId = roomId;
        this.userId = userId;
        this.isOnline = true;
        this.messageContainer = document.getElementById('messages-container');
        this.messageForm = document.getElementById('message-form');
        this.messageInput = document.getElementById('message-input');
        this.typingIndicator = document.getElementById('typing-indicator');
        this.onlineStatus = document.getElementById('online-status');
        this.lastMessageId = 0;
        this.isPolling = false;
        this.pollingInterval = null;
        this.sentMessageIds = new Set(); // Para rastrear mensajes enviados por nosotros
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadMessages();
        this.startPolling();
        this.updateOnlineStatus();
        
        // Actualizar estado online cada 30 segundos
        setInterval(() => this.updateOnlineStatus(), 30000);
    }

    setupEventListeners() {
        // Enviar mensaje
        this.messageForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.sendMessage();
        });

        // Indicador de escritura
        this.messageInput.addEventListener('input', () => {
            this.showTypingIndicator();
        });
    }

    async sendMessage() {
        const message = this.messageInput.value.trim();
        if (!message) return;

        // Deshabilitar el formulario
        this.messageForm.disabled = true;
        this.messageInput.disabled = true;

        try {
            const response = await fetch(`/chat/${this.roomId}/message`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: message
                })
            });

            const data = await response.json();

            if (data.success) {
                this.messageInput.value = '';
                
                // Agregar el mensaje inmediatamente al DOM
                this.addMessageToUI(data.message, true);
                
                // Marcar este mensaje como enviado por nosotros
                this.sentMessageIds.add(data.message.id);
                
                // Actualizar el último ID de mensaje
                if (data.message.id > this.lastMessageId) {
                    this.lastMessageId = data.message.id;
                }
                
                this.scrollToBottom();
            } else {
                this.showError('Error al enviar el mensaje');
            }
        } catch (error) {
            this.showError('Error de conexión');
        } finally {
            // Rehabilitar el formulario
            this.messageForm.disabled = false;
            this.messageInput.disabled = false;
            this.messageInput.focus();
        }
    }

    async loadMessages() {
        try {
            const response = await fetch(`/chat/${this.roomId}/messages`);
            const data = await response.json();

            if (data.success && data.messages) {
                this.messageContainer.innerHTML = '';
                data.messages.forEach(message => {
                    this.addMessageToUI(message, message.sender_id === this.userId);
                    // Actualizar el último ID de mensaje
                    if (message.id > this.lastMessageId) {
                        this.lastMessageId = message.id;
                    }
                });
                this.scrollToBottom();
            }
        } catch (error) {
            console.error('Error al cargar mensajes:', error);
        }
    }

    addMessageToUI(message, isOwnMessage) {
        // Verificar si el mensaje ya existe en el DOM
        const existingMessage = document.querySelector(`[data-message-id="${message.id}"]`);
        if (existingMessage) {
            return; // No agregar si ya existe
        }

        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${isOwnMessage ? 'justify-end' : 'justify-start'} mb-4`;
        messageDiv.setAttribute('data-message-id', message.id);
        
        const messageContent = `
            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${isOwnMessage ? 
                'bg-blue-600 text-white' : 
                'bg-gray-200 text-gray-800'
            }">
                <div class="text-sm">${this.escapeHtml(message.message)}</div>
                <div class="text-xs mt-1 opacity-75">
                    ${message.sender.name} • ${message.formatted_time || 'Ahora'}
                </div>
            </div>
        `;
        
        messageDiv.innerHTML = messageContent;
        this.messageContainer.appendChild(messageDiv);
    }

    startPolling() {
        // Polling cada 3 segundos para nuevos mensajes
        this.pollingInterval = setInterval(() => {
            this.checkForNewMessages();
        }, 3000);
    }

    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
    }

    async checkForNewMessages() {
        // Evitar polling concurrente
        if (this.isPolling) return;
        this.isPolling = true;

        try {
            const response = await fetch(`/chat/${this.roomId}/messages`);
            const data = await response.json();

            if (data.success && data.messages) {
                // Filtrar solo mensajes nuevos basándose en el ID
                const newMessages = data.messages.filter(message => message.id > this.lastMessageId);
                
                if (newMessages.length > 0) {
                    let hasOtherMessages = false;
                    let addedMessages = 0;
                    
                    newMessages.forEach(message => {
                        // Verificar si el mensaje ya existe en el DOM
                        const existingMessage = document.querySelector(`[data-message-id="${message.id}"]`);
                        if (!existingMessage) {
                            // Solo agregar si no es un mensaje que acabamos de enviar
                            if (!this.sentMessageIds.has(message.id)) {
                                this.addMessageToUI(message, message.sender_id === this.userId);
                                addedMessages++;
                                
                                // Verificar si es mensaje de otro usuario
                                if (message.sender_id !== this.userId) {
                                    hasOtherMessages = true;
                                }
                            }
                        }
                        
                        // Actualizar el último ID de mensaje
                        if (message.id > this.lastMessageId) {
                            this.lastMessageId = message.id;
                        }
                    });
                    
                    // Solo hacer scroll si se agregaron mensajes nuevos
                    if (addedMessages > 0) {
                        this.scrollToBottom();
                    }
                    
                    // Reproducir sonido de notificación solo si hay mensajes de otros usuarios
                    if (hasOtherMessages) {
                        this.playNotificationSound();
                    }
                }
            }
        } catch (error) {
            console.error('Error al verificar nuevos mensajes:', error);
        } finally {
            this.isPolling = false;
        }
    }

    async updateOnlineStatus() {
        try {
            await fetch('/chat/online-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    online: this.isOnline
                })
            });
        } catch (error) {
            console.error('Error al actualizar estado online:', error);
        }
    }

    showTypingIndicator() {
        // Implementar indicador de escritura si es necesario
    }

    scrollToBottom() {
        this.messageContainer.scrollTop = this.messageContainer.scrollHeight;
    }

    playNotificationSound() {
        // Crear y reproducir sonido de notificación
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBS13yO/eizEIHWq+8+OWT');
        audio.volume = 0.3;
        audio.play().catch(() => {
            // Ignorar errores de reproducción
        });
    }

    showError(message) {
        // Mostrar error en la interfaz
        const errorDiv = document.createElement('div');
        errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
        errorDiv.textContent = message;
        
        const container = document.querySelector('.container');
        container.insertBefore(errorDiv, container.firstChild);
        
        // Remover el error después de 5 segundos
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Limpiar recursos cuando se cierre la página
    destroy() {
        this.stopPolling();
    }
}

// Inicializar chat cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    const roomId = document.getElementById('chat-room-id')?.value;
    const userId = document.getElementById('user-id')?.value;
    
    if (roomId && userId) {
        window.chatManager = new ChatManager(roomId, userId);
    }
});

// Limpiar recursos cuando se cierre la página
window.addEventListener('beforeunload', function() {
    if (window.chatManager) {
        window.chatManager.destroy();
    }
});