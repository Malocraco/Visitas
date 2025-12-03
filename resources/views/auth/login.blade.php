@extends('layouts.auth')

@section('title', 'Iniciar Sesión - Sistema de Visitas')

@section('subtitle', 'Inicia sesión en tu cuenta')

@section('content')
<div class="space-y-6">
    <div>
        <h3 class="text-lg font-medium leading-6 text-gray-900">
            Iniciar Sesión
        </h3>
        <p class="mt-1 text-sm text-gray-500">
            Ingresa tus credenciales para acceder al sistema
        </p>
    </div>

    <form class="space-y-6" method="POST" action="{{ route('login') }}">
        @csrf
        
        <div>
            <label for="email" class="form-label">
                Correo Electrónico
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

        <div>
            <label for="password" class="form-label">
                Contraseña
            </label>
            <div class="mt-1">
                <input id="password" 
                       name="password" 
                       type="password" 
                       autocomplete="current-password" 
                       required
                       class="form-input @error('password') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror"
                       placeholder="••••••••">
            </div>
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember" 
                       name="remember" 
                       type="checkbox" 
                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-900">
                    Recordarme
                </label>
            </div>
        </div>

        <div>
            <button type="submit" class="btn-primary w-full">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Iniciar Sesión
            </button>
        </div>
    </form>

    <div class="text-center">
        <p class="text-sm text-gray-600">
            ¿No tienes una cuenta? 
            <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500">
                Regístrate aquí
            </a>
        </p>
    </div>
</div>
@endsection
