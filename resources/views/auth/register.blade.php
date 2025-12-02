@extends('layouts.auth')

@section('title', 'Registro - Sistema de Visitas')

@section('subtitle', 'Crea tu cuenta de visitante')

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ url('/') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-green transition-colors mb-4">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver al inicio
        </a>
        <h3 class="text-lg font-medium leading-6 text-gray-900">
            Registro de Visitante
        </h3>
        <p class="mt-1 text-sm text-gray-500">
            Completa la información para crear tu cuenta
        </p>
    </div>

    <form class="space-y-6" method="POST" action="{{ route('register') }}">
        @csrf
        
        <!-- Información Personal -->
        <div>
            <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                <svg class="mr-2 h-5 w-5 text-primary-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Información Personal
            </h4>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="name" class="form-label">
                        Nombre Completo *
                    </label>
                    <div class="mt-1">
                        <input id="name" 
                               name="name" 
                               type="text" 
                               autocomplete="name" 
                               required 
                               value="{{ old('name') }}"
                               class="form-input @error('name') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror"
                               placeholder="Tu nombre completo">
                    </div>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="form-label">
                        Correo Electrónico *
                    </label>
                    <div class="mt-1">
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               value="{{ old('email') }}"
                               class="form-input @error('email') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror"
                               placeholder="tu@email.com">
                    </div>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mt-6">
                <div>
                    <label for="phone" class="form-label">
                        Teléfono *
                    </label>
                    <div class="mt-1">
                        <input id="phone" 
                               name="phone" 
                               type="tel" 
                               autocomplete="tel" 
                               required 
                               value="{{ old('phone') }}"
                               class="form-input @error('phone') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror"
                               placeholder="+57 300 123 4567">
                    </div>
                    @error('phone')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="institution_type" class="form-label">
                        Tipo de Institución *
                    </label>
                    <div class="mt-1">
                        <select id="institution_type" 
                                name="institution_type" 
                                required
                                class="form-input @error('institution_type') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror">
                            <option value="">Seleccione...</option>
                            <option value="empresa" {{ old('institution_type') == 'empresa' ? 'selected' : '' }}>Empresa</option>
                            <option value="universidad" {{ old('institution_type') == 'universidad' ? 'selected' : '' }}>Universidad</option>
                            <option value="colegio" {{ old('institution_type') == 'colegio' ? 'selected' : '' }}>Colegio</option>
                            <option value="otro" {{ old('institution_type') == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                    @error('institution_type')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label for="institution_name" class="form-label">
                    Nombre de la Institución *
                </label>
                <div class="mt-1">
                    <input id="institution_name" 
                           name="institution_name" 
                           type="text" 
                           required 
                           value="{{ old('institution_name') }}"
                           class="form-input @error('institution_name') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror"
                           placeholder="Nombre de tu institución">
                </div>
                @error('institution_name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Información de Seguridad -->
        <div>
            <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                <svg class="mr-2 h-5 w-5 text-primary-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Información de Seguridad
            </h4>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="password" class="form-label">
                        Contraseña *
                    </label>
                    <div class="mt-1">
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="new-password" 
                               required
                               class="form-input @error('password') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror"
                               placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Mínimo 8 caracteres</p>
                </div>
                
                <div>
                    <label for="password_confirmation" class="form-label">
                        Confirmar Contraseña *
                    </label>
                    <div class="mt-1">
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               autocomplete="new-password" 
                               required
                               class="form-input"
                               placeholder="••••••••">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex items-center">
            <input id="terms" 
                   name="terms" 
                   type="checkbox" 
                   required
                   class="h-4 w-4 text-primary-green focus:ring-primary-green border-gray-300 rounded">
            <label for="terms" class="ml-2 block text-sm text-gray-900">
                Acepto los <a href="#" class="font-medium text-primary-green hover:text-primary-green/80">términos y condiciones</a> *
            </label>
        </div>
        
        <div>
            <button type="submit" class="btn-primary w-full">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Crear Cuenta
            </button>
        </div>
    </form>

    <div class="text-center">
        <p class="text-sm text-gray-600">
            ¿Ya tienes una cuenta? 
            <a href="{{ route('login') }}" class="font-medium text-primary-green hover:text-primary-green/80">
                Inicia sesión aquí
            </a>
        </p>
    </div>
</div>

<!-- Información adicional -->
<div class="mt-8">
    <div class="bg-primary-green/10 border border-primary-green/30 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-primary-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-primary-green">
                    ¿Por qué registrarse?
                </h3>
                <div class="mt-2 text-sm text-primary-green/80">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Solicita visitas a la institución de forma fácil y rápida</li>
                        <li>Recibe notificaciones sobre el estado de tus solicitudes</li>
                        <li>Accede al historial completo de tus visitas</li>
                        <li>Comunícate directamente con los administradores</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
