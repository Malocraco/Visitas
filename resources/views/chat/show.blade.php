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
            <button type="submit" class="btn-warning close-chat-btn">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cerrar
            </button>
        </form>
        <form action="{{ route('chat.resolve', $chatRoom->room_id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn-success resolve-chat-btn">
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

@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-4 flex items-center">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
    </svg>
    {{ session('error') }}
</div>
@endif

<!-- Chat Container -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Chat Messages -->
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
            
            <!-- Messages Container -->
            <div id="messages-container" class="h-96 overflow-y-auto p-6 space-y-4">
                @foreach($chatRoom->messages as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="relative max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }} message-bubble" data-message-id="{{ $message->id }}">
                        <div class="text-sm message-text">{{ $message->message }}</div>
                        <div class="text-xs mt-1 opacity-75 flex items-center justify-between">
                            <span>{{ $message->sender->name }} • {{ $message->getFormattedTime() }}</span>
                            @if($message->sender_id === auth()->id())
                                <button class="message-options-btn ml-2 opacity-60 hover:opacity-100 transition-opacity" data-message-id="{{ $message->id }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
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
                    <p>Este chat está cerrado</p>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <div class="space-y-6">
            <!-- Chat Info -->
            <div class="card">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Información del Chat</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Asunto</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $chatRoom->subject }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($chatRoom->status === 'open') bg-yellow-100 text-yellow-800
                            @elseif($chatRoom->status === 'active') bg-green-100 text-green-800
                            @elseif($chatRoom->status === 'closed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($chatRoom->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Visitante</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $chatRoom->visitor->name }}</p>
                        <p class="text-xs text-gray-500">{{ $chatRoom->visitor->email }}</p>
                    </div>
                    @if($chatRoom->admin)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Administrador</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $chatRoom->admin->name }}</p>
                        <p class="text-xs text-gray-500">{{ $chatRoom->admin->email }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar mensaje -->
<div id="delete-message-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 mx-auto bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
        </div>
        <div class="text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-2">¿Eliminar mensaje?</h3>
            <p class="text-sm text-gray-500 mb-6">
                Esta acción no se puede deshacer. El mensaje se eliminará permanentemente.
            </p>
            <div class="flex space-x-3 justify-center">
                <button id="cancel-delete" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancelar
                </button>
                <button id="confirm-delete" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Estilos para el dropdown de mensajes */
.message-dropdown {
    background: #2d3748;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    padding: 4px 0;
    min-width: 120px;
    z-index: 1000;
}

.dropdown-item {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    color: white;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.2s;
}

.dropdown-item:hover {
    background-color: #4a5568;
}

.dropdown-item:first-child {
    border-radius: 8px 8px 0 0;
}

.dropdown-item:last-child {
    border-radius: 0 0 8px 8px;
}

.dropdown-item svg {
    width: 16px;
    height: 16px;
    margin-right: 8px;
}

/* Estilos para el botón de opciones */
.message-options-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 2px;
    border-radius: 3px;
    transition: all 0.2s;
}

.message-options-btn:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* Estilos para mensajes del usuario (siempre a la derecha) */
.message-bubble {
    position: relative;
}

/* Estilos para el input de edición */
.message-text input {
    background: transparent;
    border: none;
    outline: none;
    color: inherit;
    font-size: inherit;
    width: 100%;
}

/* Asegurar que los mensajes del usuario estén siempre a la derecha */
.flex.justify-end {
    justify-content: flex-end !important;
}

.flex.justify-start {
    justify-content: flex-start !important;
}

/* Estilos para el modal de confirmación */
#delete-message-modal {
    backdrop-filter: blur(4px);
}

#delete-message-modal .bg-white {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>
@endpush

@push('scripts')
<script>
// Auto-resize del textarea
document.getElementById('message-input').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 100) + 'px';
});

// Agregar event listeners a los mensajes iniciales
document.addEventListener('DOMContentLoaded', function() {
    const roomId = document.getElementById('chat-room-id')?.value;
    const userId = document.getElementById('user-id')?.value;
    
    if (roomId && userId) {
        // Agregar event listeners a los mensajes que ya están en la página
        document.querySelectorAll('.message-options-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const messageId = this.dataset.messageId;
                // Usar la funcionalidad del chat manager si está disponible
                if (window.ultraSimpleChat) {
                    window.ultraSimpleChat.toggleDropdown(e, { id: messageId });
                }
            });
        });
    }
});
</script>

<!-- Chat JavaScript Ultra Simple (sin duplicación) -->
<script src="{{ asset('js/chat-ultra-simple.js') }}"></script>

<!-- JavaScript para modales de confirmación -->
<script>
let currentForm = null;

// Interceptar botones de Cerrar y Resolver
document.addEventListener('click', function(e) {
    if (e.target.closest('.close-chat-btn')) {
        e.preventDefault();
        currentForm = e.target.closest('form');
        document.getElementById('close-chat-modal').classList.remove('hidden');
    } else if (e.target.closest('.resolve-chat-btn')) {
        e.preventDefault();
        currentForm = e.target.closest('form');
        document.getElementById('resolve-chat-modal').classList.remove('hidden');
    }
});

// Función para cerrar modal de cerrar chat
function closeCloseModal() {
    document.getElementById('close-chat-modal').classList.add('hidden');
    currentForm = null;
}

// Función para confirmar cerrar chat
function confirmCloseChat() {
    document.getElementById('close-chat-modal').classList.add('hidden');
    if (currentForm) {
        currentForm.submit();
    }
    currentForm = null;
}

// Función para cerrar modal de resolver chat
function closeResolveModal() {
    document.getElementById('resolve-chat-modal').classList.add('hidden');
    currentForm = null;
}

// Función para confirmar resolver chat
function confirmResolveChat() {
    document.getElementById('resolve-chat-modal').classList.add('hidden');
    if (currentForm) {
        currentForm.submit();
    }
    currentForm = null;
}

// Event listeners para backdrop click
document.addEventListener('click', function(e) {
    if (e.target.id === 'close-chat-modal') {
        closeCloseModal();
    }
    if (e.target.id === 'resolve-chat-modal') {
        closeResolveModal();
    }
});
</script>

<!-- Modales de confirmación para acciones del chat -->
<!-- Modal de Cerrar Chat -->
<div id="close-chat-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 mx-auto bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
        </div>
        <div class="text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-2">¿Cerrar chat?</h3>
            <p class="text-sm text-gray-500 mb-6">
                El chat se cerrará y no se podrán enviar más mensajes hasta que se reactive.
            </p>
            <div class="flex space-x-3 justify-center">
                <button onclick="closeCloseModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancelar
                </button>
                <button onclick="confirmCloseChat()" class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 border border-transparent rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Resolver Chat -->
<div id="resolve-chat-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 mx-auto bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-2">¿Marcar como resuelto?</h3>
            <p class="text-sm text-gray-500 mb-6">
                El chat se marcará como resuelto, indicando que la consulta ha sido atendida completamente.
            </p>
            <div class="flex space-x-3 justify-center">
                <button onclick="closeResolveModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancelar
                </button>
                <button onclick="confirmResolveChat()" class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Resolver
                </button>
            </div>
        </div>
    </div>
</div>

@endpush

@push('styles')
<style>
/* Estilos para los modales de confirmación */
#close-chat-modal,
#resolve-chat-modal {
    backdrop-filter: blur(4px);
}

#close-chat-modal .bg-white,
#resolve-chat-modal .bg-white {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>
@endpush
