@extends('layouts.app')

@section('title', 'Editar Rol - Sistema de Visitas')

@section('content')
<div class="flex items-center justify-between py-3 mb-6 border-b border-gray-200">
    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
        <i class="fas fa-user-tag text-indigo-600 mr-2"></i>
        Editar Rol
    </h1>
    <div>
        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-user-tag mr-2 text-gray-500"></i>Información del Rol
            </h5>
        </div>
        <div class="p-6">
                <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag mr-1"></i>Nombre del Rol
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $role->name) }}" 
                                   {{ in_array($role->name, ['superadmin', 'administrador', 'visitante']) ? 'readonly' : '' }}
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                @if(in_array($role->name, ['superadmin', 'administrador', 'visitante']))
                                    Nombre del rol del sistema (no editable).
                                @else
                                    Nombre único para identificar el rol.
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-1"></i>Descripción
                            </label>
                            <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Descripción opcional del rol y sus responsabilidades.
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-key mr-1"></i>Permisos Asignados
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($permissions as $permission)
                            <label class="inline-flex items-start space-x-2 p-2 rounded-lg border border-gray-200 hover:bg-gray-50">
                                <input class="mt-1 h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" 
                                       type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $permission->id }}" 
                                       id="permission_{{ $permission->id }}"
                                       {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                <span>
                                    <span class="block text-sm font-semibold text-gray-900">{{ ucfirst($permission->name) }}</span>
                                    @if($permission->description)
                                        <span class="block text-xs text-gray-500">{{ $permission->description }}</span>
                                    @endif
                                </span>
                            </label>
                            @endforeach
                        </div>
                        @error('permissions')
                            <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Selecciona los permisos que tendrán los usuarios con este rol.
                        </p>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-save mr-2"></i>Actualizar Rol
                        </button>
                    </div>
                </form>
        </div>
    </div>
    
    <div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-gray-500"></i>Información del Rol
                </h5>
            </div>
            <div class="p-6">
                <div class="mb-3">
                    <span class="font-semibold text-gray-700">ID:</span> #{{ $role->id }}
                </div>
                <div class="mb-3">
                    <span class="font-semibold text-gray-700">Fecha de Creación:</span><br>
                    <span class="text-gray-600">{{ $role->created_at ? $role->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                </div>
                <div class="mb-3">
                    <span class="font-semibold text-gray-700">Última Actualización:</span><br>
                    <span class="text-gray-600">{{ $role->updated_at ? $role->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                </div>
                <div class="mb-3">
                    <span class="font-semibold text-gray-700">Usuarios Asignados:</span><br>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">{{ $role->users()->count() }} usuarios</span>
                </div>
                <div class="mb-3">
                    <span class="font-semibold text-gray-700">Permisos Actuales:</span><br>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $role->permissions()->count() }} permisos</span>
                </div>
                
                @if(in_array($role->name, ['superadmin', 'administrador', 'visitante']))
                <div class="mt-2 rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <span class="font-semibold">Nota:</span> Este es un rol del sistema. Los cambios se aplicarán a todos los usuarios con este rol.
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mt-3">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-shield-alt mr-2 text-gray-500"></i>Seguridad
                </h5>
            </div>
            <div class="p-6">
                <div class="rounded-lg border border-sky-200 bg-sky-50 p-4 text-sky-800">
                    <h6 class="font-semibold mb-2"><i class="fas fa-lightbulb mr-1"></i>Consejos:</h6>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        <li>Los cambios en permisos se aplicarán inmediatamente.</li>
                        <li>Verifica que los permisos asignados sean apropiados.</li>
                        <li>Los usuarios con este rol tendrán acceso a las funciones seleccionadas.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
