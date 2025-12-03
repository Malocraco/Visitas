@extends('layouts.app')

@section('title', 'Crear Nuevo Chat - Sistema de Visitas')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus text-primary me-2"></i>
        Crear Nuevo Chat
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('chat.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-comments me-2"></i>Iniciar Conversación
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>¿Necesitas ayuda?</strong> Crea un nuevo chat para comunicarte directamente con nuestros administradores.
                    Te responderemos lo antes posible.
                </div>

                <form action="{{ route('chat.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label">
                            <i class="fas fa-tag me-1"></i>Asunto del Chat
                        </label>
                        <input type="text" 
                               class="form-control @error('subject') is-invalid @enderror" 
                               id="subject" 
                               name="subject" 
                               value="{{ old('subject') }}" 
                               placeholder="Ej: Problema con agendamiento de visita"
                               required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Describe brevemente el motivo de tu consulta.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">
                            <i class="fas fa-comment me-1"></i>Mensaje Inicial
                        </label>
                        <textarea class="form-control @error('message') is-invalid @enderror" 
                                  id="message" 
                                  name="message" 
                                  rows="5" 
                                  placeholder="Describe tu consulta o problema en detalle..."
                                  required>{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Proporciona todos los detalles necesarios para que podamos ayudarte mejor.
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Tiempo de respuesta:</strong> Nuestros administradores revisan los chats regularmente 
                        y te responderán lo antes posible, generalmente en menos de 24 horas.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('chat.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i>Crear Chat
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-question-circle me-2"></i>¿Cuándo usar el chat?
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">
                            <i class="fas fa-check-circle me-1"></i>Casos de uso:
                        </h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-arrow-right text-success me-2"></i>Problemas con agendamiento</li>
                            <li><i class="fas fa-arrow-right text-success me-2"></i>Consultas sobre fechas disponibles</li>
                            <li><i class="fas fa-arrow-right text-success me-2"></i>Cambios en visitas programadas</li>
                            <li><i class="fas fa-arrow-right text-success me-2"></i>Información adicional requerida</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>No usar para:
                        </h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-times text-danger me-2"></i>Emergencias urgentes</li>
                            <li><i class="fas fa-times text-danger me-2"></i>Información confidencial</li>
                            <li><i class="fas fa-times text-danger me-2"></i>Reclamos formales</li>
                            <li><i class="fas fa-times text-danger me-2"></i>Consultas técnicas complejas</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Contador de caracteres para el mensaje
document.getElementById('message').addEventListener('input', function() {
    const maxLength = 1000;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;
    
    // Crear o actualizar el contador
    let counter = document.getElementById('char-counter');
    if (!counter) {
        counter = document.createElement('div');
        counter.id = 'char-counter';
        counter.className = 'form-text';
        this.parentNode.appendChild(counter);
    }
    
    if (remaining < 0) {
        counter.innerHTML = `<span class="text-danger">Has excedido el límite de ${maxLength} caracteres</span>`;
    } else if (remaining < 100) {
        counter.innerHTML = `<span class="text-warning">Te quedan ${remaining} caracteres</span>`;
    } else {
        counter.innerHTML = `Te quedan ${remaining} caracteres`;
    }
});
</script>
@endpush
