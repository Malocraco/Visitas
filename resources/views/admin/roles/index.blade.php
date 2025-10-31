@extends('layouts.app')

@section('title', 'Roles y Permisos - Sistema de Visitas')

@section('content')
<div class="flex items-center justify-between py-3 mb-6 border-b border-gray-200">
    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
        <i class="fas fa-user-cog text-indigo-600 mr-2"></i>
        Roles y Permisos
    </h1>
    <div></div>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl text-white shadow-lg hover:shadow-xl transition-shadow">
        <div class="p-6 flex items-center justify-between">
            <div>
                <p class="text-indigo-100 text-sm font-medium mb-1">Total Roles</p>
                <h4 class="text-4xl font-bold">{{ $roles->total() }}</h4>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-4">
                <i class="fas fa-user-tag fa-2x"></i>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl text-white shadow-lg hover:shadow-xl transition-shadow">
        <div class="p-6 flex items-center justify-between">
            <div>
                <p class="text-emerald-100 text-sm font-medium mb-1">Usuarios Asignados</p>
                <h4 class="text-4xl font-bold">{{ $roles->sum('users_count') }}</h4>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-4">
                <i class="fas fa-users fa-2x"></i>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl text-white shadow-lg hover:shadow-xl transition-shadow">
        <div class="p-6 flex items-center justify-between">
            <div>
                <p class="text-sky-100 text-sm font-medium mb-1">Permisos Disponibles</p>
                <h4 class="text-4xl font-bold">{{ \App\Models\Permission::count() ?? 0 }}</h4>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-4">
                <i class="fas fa-key fa-2x"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Roles -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-list mr-2 text-gray-500"></i>Lista de Roles
        </h5>
    </div>
    <div class="p-6">
        @if($roles->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Usuarios</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Permisos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha Creación</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($roles as $role)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $role->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <div class="mr-2">
                                    @switch($role->name)
                                        @case('superadmin')
                                            <i class="fas fa-crown fa-lg text-red-600"></i>
                                            @break
                                        @case('administrador')
                                            <i class="fas fa-user-shield fa-lg text-yellow-500"></i>
                                            @break
                                        @case('visitante')
                                            <i class="fas fa-user fa-lg text-green-600"></i>
                                            @break
                                        @default
                                            <i class="fas fa-user-tag fa-lg text-gray-500"></i>
                                    @endswitch
                                </div>
                                <div class="font-semibold">
                                    {{ ucfirst($role->name) }}
                                    @if($role->name === 'superadmin')
                                        <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Sistema</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $role->description ?? 'Sin descripción' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">{{ $role->users_count }} usuarios</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                             @php
                                 $permissionsCount = $role->permissions ? $role->permissions->count() : 0;
                             @endphp
                             @if($permissionsCount > 0)
                                 <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $permissionsCount }} permisos</span>
                             @else
                                 <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Sin permisos</span>
                             @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $role->created_at ? $role->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.roles.edit', $role) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-colors" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if(!in_array($role->name, ['superadmin', 'administrador', 'visitante']) && $role->users_count === 0)
                                <button type="button" 
                                        class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors" 
                                        onclick="confirmDelete({{ $role->id }}, '{{ $role->name }}')"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                                
                                <form id="delete-form-{{ $role->id }}" 
                                      action="{{ route('admin.roles.destroy', $role) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="flex justify-center mt-6">{{ $roles->links() }}</div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-user-tag fa-3x text-gray-400 mb-3"></i>
                <h5 class="text-gray-600">No hay roles configurados</h5>
                <p class="text-gray-500">Los roles del sistema deben ser configurados por el administrador.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" id="deleteModal">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="document.getElementById('deleteModal').classList.add('hidden')">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="p-6">
                <p class="text-gray-700">¿Estás seguro de que quieres eliminar el rol <strong id="roleName"></strong>?</p>
                <p class="text-sm text-red-600 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Esta acción no se puede deshacer.
                </p>
            </div>
            <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200">
                <button type="button" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none" onclick="document.getElementById('deleteModal').classList.add('hidden')">Cancelar</button>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none" id="confirmDelete">
                    <i class="fas fa-trash mr-2"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(roleId, roleName) {
    document.getElementById('roleName').textContent = roleName;
    document.getElementById('confirmDelete').onclick = function() {
        document.getElementById('delete-form-' + roleId).submit();
    };
    
    document.getElementById('deleteModal').classList.remove('hidden');
}
</script>
@endpush
