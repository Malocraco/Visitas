// Chat ultra simple - Sin duplicación garantizada
class UltraSimpleChat {
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
                // Recargar TODOS los mensajes para evitar duplicación
                await this.loadMessages();
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
                // Limpiar completamente el contenedor
                this.messageContainer.innerHTML = '';
                
                // Agregar todos los mensajes
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
        // Determinar si es mensaje del usuario actual (usar siempre esta lógica)
        const isCurrentUser = message.sender_id == this.userId;
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${isCurrentUser ? 'justify-end' : 'justify-start'} mb-4`;
        messageDiv.setAttribute('data-message-id', message.id);
        
        const messageContent = `
            <div class="relative max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${isCurrentUser ? 
                'bg-blue-600 text-white' : 
                'bg-gray-200 text-gray-800'
            } message-bubble" data-message-id="${message.id}">
                <div class="text-sm message-text">${this.escapeHtml(message.message)}</div>
                <div class="text-xs mt-1 opacity-75 flex items-center justify-between">
                    <span>${message.sender.name} • ${message.formatted_time || 'Ahora'}</span>
                    ${isCurrentUser ? `
                        <button class="message-options-btn ml-2 opacity-60 hover:opacity-100 transition-opacity" data-message-id="${message.id}">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    ` : ''}
                </div>
            </div>
        `;
        
        messageDiv.innerHTML = messageContent;
        this.messageContainer.appendChild(messageDiv);
        
        // Agregar event listeners para el menú desplegable
        if (isCurrentUser) {
            this.addDropdownListeners(messageDiv, message);
        }
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

    addDropdownListeners(messageDiv, message) {
        const optionsBtn = messageDiv.querySelector('.message-options-btn');
        
        optionsBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleDropdown(e, message);
        });
    }

    toggleDropdown(event, message) {
        // Remover dropdown anterior si existe
        this.hideDropdown();
        
        const dropdown = document.createElement('div');
        dropdown.className = 'message-dropdown';
        dropdown.innerHTML = `
            <div class="dropdown-item" data-action="edit" data-message-id="${message.id}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
            </div>
            <div class="dropdown-item" data-action="delete" data-message-id="${message.id}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Eliminar
            </div>
        `;
        
        // Posicionar el dropdown cerca del botón
        const buttonRect = event.target.getBoundingClientRect();
        dropdown.style.position = 'fixed';
        dropdown.style.left = (buttonRect.left - 80) + 'px';
        dropdown.style.top = (buttonRect.bottom + 5) + 'px';
        dropdown.style.zIndex = '1000';
        
        document.body.appendChild(dropdown);
        
        // Event listeners para las opciones del dropdown
        dropdown.addEventListener('click', (e) => {
            const action = e.target.closest('.dropdown-item')?.dataset.action;
            const messageId = e.target.closest('.dropdown-item')?.dataset.messageId;
            
            if (action === 'edit') {
                this.editMessage(messageId);
            } else if (action === 'delete') {
                this.deleteMessage(messageId);
            }
            
            this.hideDropdown();
        });
        
        // Ocultar dropdown al hacer clic fuera
        setTimeout(() => {
            document.addEventListener('click', this.hideDropdown.bind(this), { once: true });
        }, 100);
    }

    hideDropdown() {
        const existingDropdown = document.querySelector('.message-dropdown');
        if (existingDropdown) {
            existingDropdown.remove();
        }
    }

    async editMessage(messageId) {
        const messageElement = document.querySelector(`[data-message-id="${messageId}"] .message-text`);
        const originalText = messageElement.textContent;
        
        // Crear input de edición
        const editInput = document.createElement('input');
        editInput.type = 'text';
        editInput.value = originalText;
        editInput.className = 'w-full bg-transparent border-none outline-none text-sm';
        editInput.style.color = 'inherit';
        
        // Reemplazar el texto con el input
        messageElement.innerHTML = '';
        messageElement.appendChild(editInput);
        editInput.focus();
        editInput.select();
        
        const saveEdit = async () => {
            const newText = editInput.value.trim();
            if (newText && newText !== originalText) {
                try {
                    const response = await fetch(`/chat/${this.roomId}/message/${messageId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            message: newText
                        })
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        messageElement.textContent = newText;
                        // Recargar mensajes para sincronizar
                        await this.loadMessages();
                    } else {
                        messageElement.textContent = originalText;
                        this.showError('Error al editar el mensaje');
                    }
                } catch (error) {
                    messageElement.textContent = originalText;
                    this.showError('Error de conexión');
                }
            } else {
                messageElement.textContent = originalText;
            }
        };
        
        const cancelEdit = () => {
            messageElement.textContent = originalText;
        };
        
        // Event listeners
        editInput.addEventListener('blur', saveEdit);
        editInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                saveEdit();
            } else if (e.key === 'Escape') {
                e.preventDefault();
                cancelEdit();
            }
        });
    }

    async deleteMessage(messageId) {
        // Mostrar modal de confirmación
        const confirmed = await this.showDeleteConfirmation();
        if (!confirmed) {
            return;
        }
        
        try {
            const response = await fetch(`/chat/${this.roomId}/message/${messageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            if (data.success) {
                // Recargar mensajes para actualizar la vista
                await this.loadMessages();
            } else {
                this.showError('Error al eliminar el mensaje');
            }
        } catch (error) {
            this.showError('Error de conexión');
        }
    }

    showDeleteConfirmation() {
        return new Promise((resolve) => {
            const modal = document.getElementById('delete-message-modal');
            const cancelBtn = document.getElementById('cancel-delete');
            const confirmBtn = document.getElementById('confirm-delete');
            
            // Mostrar modal
            modal.classList.remove('hidden');
            
            // Event listeners
            const handleCancel = () => {
                modal.classList.add('hidden');
                resolve(false);
                cleanup();
            };
            
            const handleConfirm = () => {
                modal.classList.add('hidden');
                resolve(true);
                cleanup();
            };
            
            const cleanup = () => {
                cancelBtn.removeEventListener('click', handleCancel);
                confirmBtn.removeEventListener('click', handleConfirm);
                modal.removeEventListener('click', handleBackdrop);
            };
            
            const handleBackdrop = (e) => {
                if (e.target === modal) {
                    handleCancel();
                }
            };
            
            cancelBtn.addEventListener('click', handleCancel);
            confirmBtn.addEventListener('click', handleConfirm);
            modal.addEventListener('click', handleBackdrop);
        });
    }
}

// Inicializar chat ultra simple cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    const roomId = document.getElementById('chat-room-id')?.value;
    const userId = document.getElementById('user-id')?.value;
    
    if (roomId && userId) {
        window.ultraSimpleChat = new UltraSimpleChat(roomId, userId);
    }
});
