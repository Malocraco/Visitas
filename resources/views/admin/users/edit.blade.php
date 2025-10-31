@extends('layouts.app')

@section('title', 'Editar Usuario - Sistema de Visitas')

@section('content')
<div class="flex items-center justify-between py-3 mb-6 border-b border-gray-200">
    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
        <i class="fas fa-user-edit text-indigo-600 mr-2"></i>
        Editar Usuario
    </h1>
    <div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Formulario Principal -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900 flex items-center mb-0">
                    <i class="fas fa-user-edit mr-2 text-indigo-600"></i>Información del Usuario
                </h5>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-1 text-gray-500"></i>Nombre Completo
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-1 text-gray-500"></i>Correo Electrónico
                            </label>
                            <input type="email" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-1 text-gray-500"></i>Nueva Contraseña
                            </label>
                            <input type="password" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror" 
                                   id="password" 
                                   name="password">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Deja en blanco para mantener la contraseña actual.
                            </p>
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-1 text-gray-500"></i>Confirmar Nueva Contraseña
                            </label>
                            <input type="password" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                   id="password_confirmation" 
                                   name="password_confirmation">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-tag mr-1 text-gray-500"></i>Rol
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white @error('role_id') border-red-500 @enderror" 
                                    id="role_id" 
                                    name="role_id" 
                                    required>
                                <option value="">Seleccionar rol...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" 
                                            {{ old('role_id', $user->getPrimaryRole()->id ?? '') == $role->id ? 'selected' : '' }}>
                                        @switch($role->name)
                                            @case('superadmin')
                                                👑 Super Administrador
                                                @break
                                            @case('administrador')
                                                🛡️ Administrador
                                                @break
                                            @case('visitante')
                                                👤 Visitante
                                                @break
                                            @default
                                                {{ ucfirst($role->name) }}
                                        @endswitch
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone mr-1 text-gray-500"></i>Teléfono
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('contact_phone') border-red-500 @enderror" 
                                   id="contact_phone" 
                                   name="contact_phone" 
                                   value="{{ old('contact_phone', $user->phone) }}">
                            @error('contact_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="institution_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building mr-1 text-gray-500"></i>Institución
                        </label>
                        <input type="text" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('institution_name') border-red-500 @enderror" 
                               id="institution_name" 
                               name="institution_name" 
                               value="{{ old('institution_name', $user->institution_name) }}">
                        @error('institution_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Campo opcional. Solo aplica para visitantes.
                        </p>
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                            <i class="fas fa-save mr-2"></i>Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Sidebar de Información -->
    <div class="space-y-6">
        <!-- Información del Usuario -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900 flex items-center mb-0">
                    <i class="fas fa-info-circle mr-2 text-indigo-600"></i>Información del Usuario
                </h5>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <span class="text-sm font-medium text-gray-500">ID:</span>
                        <p class="text-sm text-gray-900 font-semibold">#{{ $user->id }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Fecha de Registro:</span>
                        <p class="text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Última Actualización:</span>
                        <p class="text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    @if($user->id === auth()->id())
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2 mt-0.5"></i>
                            <div>
                                <p class="text-sm font-medium text-yellow-800">Nota:</p>
                                <p class="text-xs text-yellow-700 mt-1">Estás editando tu propia cuenta.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-blue-600 mr-2 mt-0.5"></i>
                            <div>
                                <h6 class="text-sm font-semibold text-blue-800 mb-1">Restricción de Seguridad:</h6>
                                <p class="text-xs text-blue-700">
                                    <i class="fas fa-lock mr-1"></i>
                                    <strong>El rol de Super Administrador no se puede asignar desde esta interfaz</strong> por motivos de seguridad. Solo los Super Administradores existentes pueden otorgar este privilegio directamente en la base de datos.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Consejos de Seguridad -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900 flex items-center mb-0">
                    <i class="fas fa-shield-alt mr-2 text-indigo-600"></i>Seguridad
                </h5>
            </div>
            <div class="p-6">
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h6 class="text-sm font-semibold text-blue-800 mb-2 flex items-center">
                        <i class="fas fa-lightbulb mr-2"></i>Consejos:
                    </h6>
                    <ul class="text-xs text-blue-700 space-y-1 list-disc list-inside">
                        <li>Solo cambia la contraseña si es necesario.</li>
                        <li>Verifica que el rol asignado sea el correcto.</li>
                        <li>Los cambios se aplicarán inmediatamente.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
