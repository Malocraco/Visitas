// Chat simple sin polling - Solución temporal
class SimpleChatManager {
    constructor(roomId, userId) {
        this.roomId = roomId;
        this.userId = userId;
        this.messageContainer = document.getElementById('messages-container');
        this.messageForm = document.getElementById('message-form');
        this.messageInput = document.getElementById('message-input');
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadMessages();
    }

    setupEventListeners() {
        // Enviar mensaje
        this.messageForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.sendMessage();
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
                // Recargar todos los mensajes después de enviar
                this.loadMessages();
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
                });
                this.scrollToBottom();
            }
        } catch (error) {
            console.error('Error al cargar mensajes:', error);
        }
    }

    addMessageToUI(message, isOwnMessage) {
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

    scrollToBottom() {
        this.messageContainer.scrollTop = this.messageContainer.scrollHeight;
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
}

// Inicializar chat simple cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    const roomId = document.getElementById('chat-room-id')?.value;
    const userId = document.getElementById('user-id')?.value;
    
    if (roomId && userId) {
        window.simpleChatManager = new SimpleChatManager(roomId, userId);
    }
});
