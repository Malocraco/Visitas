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
                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }}">
                        <div class="text-sm">{{ $message->message }}</div>
                        <div class="text-xs mt-1 opacity-75">
                            {{ $message->sender->name }} • {{ $message->getFormattedTime() }}
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
@endsection

@push('scripts')
<script>
// Auto-resize del textarea
document.getElementById('message-input').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 100) + 'px';
});
</script>

<!-- Chat JavaScript Ultra Simple (sin duplicación) -->
<script src="{{ asset('js/chat-ultra-simple.js') }}"></script>
@endpush
