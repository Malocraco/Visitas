@extends('layouts.app')

@section('title', 'Chat - ' . $chatRoom->subject)

@section('content')
<!-- Hidden inputs for JavaScript -->
<input type="hidden" id="chat-room-id" value="{{ $chatRoom->room_id }}">
<input type="hidden" id="user-id" value="{{ auth()->id() }}">
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
        <svg class="w-6 h-6 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        Chat: {{ $chatRoom->subject }}
    </h1>
    <div class="flex space-x-2">
        <a href="{{ route('chat.index') }}" class="btn-secondary">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver
        </a>
        @if((auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()) && $chatRoom->isActive())
        <form action="{{ route('chat.close', $chatRoom->room_id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn-warning" 
                    onclick="return confirm('¿Estás seguro de cerrar este chat?')">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cerrar
            </button>
        </form>
        <form action="{{ route('chat.resolve', $chatRoom->room_id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn-success" 
                    onclick="return confirm('¿Marcar como resuelto?')">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Resolver
            </button>
        </form>
        @endif
    </div>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-4 flex items-center">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Información del Chat -->
    <div class="lg:col-span-1">
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Información del Chat
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <span class="font-medium text-gray-900">Estado:</span>
                    <div class="mt-1">
                        @switch($chatRoom->status)
                            @case('open')
                                <span class="badge-warning">Abierto</span>
                                @break
                            @case('active')
                                <span class="badge-primary">Activo</span>
                                @break
                            @case('closed')
                                <span class="badge-secondary">Cerrado</span>
                                @break
                            @case('resolved')
                                <span class="badge-success">Resuelto</span>
                                @break
                        @endswitch
                    </div>
                </div>
                
                <div>
                    <span class="font-medium text-gray-900">Visitante:</span>
                    <div class="flex items-center mt-2">
                        <svg class="w-8 h-8 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div>
                            <div class="font-medium text-gray-900">{{ $chatRoom->visitor->name }}</div>
                            <div class="text-sm text-gray-500">{{ $chatRoom->visitor->email }}</div>
                        </div>
                    </div>
                </div>
                
                @if($chatRoom->admin)
                <div>
                    <span class="font-medium text-gray-900">Administrador:</span>
                    <div class="flex items-center mt-2">
                        <svg class="w-8 h-8 text-success-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <div>
                            <div class="font-medium text-gray-900">{{ $chatRoom->admin->name }}</div>
                            <div class="text-sm text-gray-500">{{ $chatRoom->admin->email }}</div>
                        </div>
                    </div>
                </div>
                @endif
                
                <div>
                    <span class="font-medium text-gray-900">Creado:</span>
                    <div class="text-sm text-gray-500">{{ $chatRoom->created_at->format('d/m/Y H:i') }}</div>
                </div>
                
                @if($chatRoom->last_message_at)
                <div>
                    <span class="font-medium text-gray-900">Último mensaje:</span>
                    <div class="text-sm text-gray-500">{{ $chatRoom->last_message_at->format('d/m/Y H:i') }}</div>
                </div>
                @endif
                
                <div>
                    <span class="font-medium text-gray-900">Total mensajes:</span>
                    <div class="mt-1">
                        <span class="badge-info">{{ $chatRoom->messages->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Estado de conexión -->
        <div class="card mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                    </svg>
                    Estado de Conexión
                </h3>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex justify-between items-center">
                    <span class="font-medium text-gray-900">Visitante:</span>
                    @if($chatRoom->visitor_online)
                        <span class="badge-success">Online</span>
                    @else
                        <span class="badge-secondary">Offline</span>
                    @endif
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-medium text-gray-900">Administrador:</span>
                    @if($chatRoom->admin_online)
                        <span class="badge-success">Online</span>
                    @else
                        <span class="badge-secondary">Offline</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Área de Chat -->
    <div class="lg:col-span-3">
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Conversación
                </h3>
            </div>
            <div class="overflow-hidden">
                <!-- Mensajes -->
                <div id="chat-messages" class="h-96 overflow-y-auto p-4 bg-gray-50">
                    @foreach($chatRoom->messages as $message)
                    <div class="message {{ $message->sender_id === auth()->id() ? 'message-own' : 'message-other' }} mb-4">
                        <div class="message-content">
                            <div class="message-header flex justify-between items-center mb-2 text-sm">
                                <span class="message-sender font-medium flex items-center">
                                    @if($message->isFromAdmin())
                                        <svg class="w-4 h-4 text-success-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-primary-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    @endif
                                    {{ $message->sender->name }}
                                </span>
                                <span class="message-time text-gray-500">{{ $message->getFormattedTime() }}</span>
                            </div>
                            <div class="message-text">{{ $message->message }}</div>
                            @if($message->hasAttachment())
                            <div class="message-attachment mt-2">
                                <a href="{{ $message->getAttachmentUrl() }}" target="_blank" class="btn-secondary">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                    {{ $message->attachment_name }}
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Formulario de envío -->
                @if($chatRoom->isActive())
                <div class="p-4 border-t border-gray-200 bg-white">
                    <form id="message-form">
                        @csrf
                        <div class="flex">
                            <textarea class="form-input flex-1 rounded-r-none" id="message-input" name="message" 
                                      placeholder="Escribe tu mensaje..." rows="2" required></textarea>
                            <button type="submit" class="btn-primary rounded-l-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    <div class="text-center text-gray-500">
                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <p>Este chat está cerrado y no se pueden enviar más mensajes.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.message {
    display: flex;
}

.message-own {
    justify-content: flex-end;
}

.message-other {
    justify-content: flex-start;
}

.message-content {
    max-width: 70%;
    padding: 0.75rem;
    border-radius: 1rem;
    position: relative;
}

.message-own .message-content {
    background-color: #2563eb;
    color: white;
    border-bottom-right-radius: 0.25rem;
}

.message-other .message-content {
    background-color: white;
    border: 1px solid #e5e7eb;
    border-bottom-left-radius: 0.25rem;
}

.message-own .message-attachment .btn-secondary {
    background-color: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
    color: white;
}

.message-own .message-attachment .btn-secondary:hover {
    background-color: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.4);
    color: white;
}
</style>
@endpush

@push('scripts')
<script>
let lastMessageId = {{ $chatRoom->messages->last() ? $chatRoom->messages->last()->id : 0 }};
let isTyping = false;

// Función para enviar mensaje
document.getElementById('message-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageInput = document.getElementById('message-input');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    // Deshabilitar formulario
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
    
    fetch('{{ route("chat.send-message", $chatRoom->room_id) }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Limpiar formulario
            messageInput.value = '';
            document.getElementById('file-name').textContent = '';
            document.getElementById('attachment').value = '';
            
            // Agregar mensaje a la vista
            addMessageToView(data.message, true);
            
            // Actualizar último mensaje
            lastMessageId = data.message.id;
        } else {
            alert('Error al enviar mensaje: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al enviar mensaje');
    })
    .finally(() => {
        // Rehabilitar formulario
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Función para agregar mensaje a la vista
function addMessageToView(message, isOwn = false) {
    const messagesContainer = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${isOwn ? 'message-own' : 'message-other'} mb-4`;
    
    const attachmentHtml = message.attachment_path ? 
        `<div class="message-attachment mt-2">
            <a href="/storage/${message.attachment_path}" target="_blank" class="btn-secondary">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                </svg>
                ${message.attachment_name}
            </a>
        </div>` : '';
    
    messageDiv.innerHTML = `
        <div class="message-content">
            <div class="message-header flex justify-between items-center mb-2 text-sm">
                <span class="message-sender font-medium flex items-center">
                    ${message.sender_type === 'admin' ? 
                        '<svg class="w-4 h-4 text-success-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>' : 
                        '<svg class="w-4 h-4 text-primary-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>'}
                    ${message.sender.name}
                </span>
                <span class="message-time text-gray-500">${message.formatted_time}</span>
            </div>
            <div class="message-text">${message.message}</div>
            ${attachmentHtml}
        </div>
    `;
    
    messagesContainer.appendChild(messageDiv);
    scrollToBottom();
}

// Función para hacer scroll al final
function scrollToBottom() {
    const messagesContainer = document.getElementById('chat-messages');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Función para obtener nuevos mensajes
function getNewMessages() {
    fetch('{{ route("chat.get-messages", $chatRoom->room_id) }}')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const newMessages = data.messages.filter(msg => msg.id > lastMessageId);
            
            newMessages.forEach(message => {
                const isOwn = message.sender_id === {{ auth()->id() }};
                addMessageToView(message, isOwn);
                lastMessageId = message.id;
            });
        }
    })
    .catch(error => {
        console.error('Error getting messages:', error);
    });
}

// Actualizar estado online
function updateOnlineStatus(online) {
    fetch('{{ route("chat.online-status") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ online: online })
    });
}

// Configurar eventos
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
    
    // Obtener nuevos mensajes cada 3 segundos
    setInterval(getNewMessages, 3000);
    
    // Actualizar estado online cada 30 segundos
    setInterval(() => updateOnlineStatus(true), 30000);
    
    // Marcar como online al cargar
    updateOnlineStatus(true);
    
    // Marcar como offline al cerrar
    window.addEventListener('beforeunload', () => updateOnlineStatus(false));
});

// Manejar archivos adjuntos
document.getElementById('attachment').addEventListener('change', function() {
    const fileName = this.files[0] ? this.files[0].name : '';
    document.getElementById('file-name').textContent = fileName;
});

// Auto-resize del textarea
document.getElementById('message-input').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 100) + 'px';
});
</script>

<!-- Chat JavaScript Ultra Simple (sin duplicación) -->
<script src="{{ asset('js/chat-ultra-simple.js') }}"></script>
@endpush
