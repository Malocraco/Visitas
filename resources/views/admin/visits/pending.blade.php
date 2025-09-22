@extends('layouts.app')

@section('title', 'Solicitudes Pendientes - Sistema de Visitas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <svg class="h-8 w-8 text-yellow-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Solicitudes Pendientes
            </h1>
            <p class="mt-1 text-sm text-gray-500">Revisa y gestiona las solicitudes de visita pendientes de aprobación</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3"></div>
    </div>

    @if($pendingVisits->count() > 0)
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-3">
            @foreach($pendingVisits as $visit)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300 overflow-hidden">
                <!-- Header de la tarjeta -->
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-b border-yellow-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Solicitud #{{ $visit->id }}</h3>
                                <p class="text-sm text-gray-600">ID: {{ $visit->id }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pendiente
                        </span>
                    </div>
                </div>

                <!-- Contenido de la tarjeta -->
                <div class="p-6 space-y-6">
                    <!-- Información del Visitante -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <h4 class="text-sm font-semibold text-gray-900">Visitante</h4>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-gray-700">
                                <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="font-medium">{{ $visit->user->name }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $visit->user->email }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $visit->user->phone }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información de la Institución -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h4 class="text-sm font-semibold text-gray-900">Institución</h4>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-900">{{ $visit->user->institution_name }}</p>
                            <p class="text-xs text-gray-600 bg-blue-100 px-2 py-1 rounded-full inline-block">{{ ucfirst($visit->user->institution_type) }}</p>
                        </div>
                    </div>
                    
                    <!-- Propósito -->
                    @if($visit->purpose)
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h4 class="text-sm font-semibold text-gray-900">Propósito</h4>
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ Str::limit($visit->purpose, 120) }}</p>
                    </div>
                    @endif
                    
                    <!-- Fecha y Hora -->
                    <div class="bg-purple-50 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h4 class="text-sm font-semibold text-gray-900">Fecha de Visita</h4>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-gray-700">
                                <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="font-medium">{{ $visit->preferred_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $visit->preferred_start_time->format('H:i') }} - {{ $visit->preferred_end_time->format('H:i') }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actividades -->
                    <div class="bg-indigo-50 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h4 class="text-sm font-semibold text-gray-900">Actividades</h4>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($visit->activities as $activity)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $activity->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Fecha de Solicitud -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-900">Solicitado</span>
                            </div>
                            <span class="text-sm text-gray-600">{{ $visit->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer con botones de acción -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex space-x-3">
                        <button type="button" 
                                onclick="openApproveModal({{ $visit->id }}, '{{ $visit->user->name }}', '{{ $visit->preferred_date->format('d/m/Y') }}')"
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Aprobar
                        </button>
                        <button type="button" 
                                onclick="openPostponeModal({{ $visit->id }}, '{{ $visit->user->name }}', '{{ $visit->preferred_date->format('d/m/Y') }}')"
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Posponer
                        </button>
                        <a href="{{ route('admin.visits.details', $visit->id) }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Ver
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Paginación -->
        <div class="flex justify-center mt-8">
            {{ $pendingVisits->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <div class="mx-auto w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">¡Excelente!</h3>
            <p class="text-gray-600 mb-4">No hay solicitudes pendientes en este momento.</p>
            <p class="text-sm text-gray-500">Todas las solicitudes han sido procesadas correctamente.</p>
        </div>
    @endif
</div>

<!-- Modal Aprobar -->
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Aprobar Visita</h3>
                        <p class="text-sm text-gray-500">Confirmar aprobación de solicitud</p>
                    </div>
                </div>
                <button type="button" onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <div class="mb-6">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm font-medium text-green-800">¿Estás seguro de que deseas aprobar esta visita?</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Visitante:</span>
                            <span class="text-sm text-gray-900" id="approveVisitorName"></span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Fecha:</span>
                            <span class="text-sm text-gray-900" id="approveVisitDate"></span>
                        </div>
                    </div>
                </div>
                
                <form id="approveForm" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notas del Administrador
                            <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <textarea id="admin_notes" 
                                  name="admin_notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"
                                  placeholder="Agregar notas adicionales sobre la aprobación..."></textarea>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex space-x-3">
                        <button type="button" 
                                onclick="closeApproveModal()"
                                class="flex-1 px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Aprobar Visita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Posponer -->
<div id="postponeModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Posponer Visita</h3>
                        <p class="text-sm text-gray-500">Reagendar para otro día disponible</p>
                    </div>
                </div>
                <button type="button" onclick="closePostponeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <div class="mb-6">
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm font-medium text-orange-800">¿Deseas posponer esta visita para otro día?</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Visitante:</span>
                            <span class="text-sm text-gray-900" id="postponeVisitorName"></span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Fecha actual:</span>
                            <span class="text-sm text-gray-900" id="postponeVisitDate"></span>
                        </div>
                    </div>
                </div>
                
                <form id="postponeForm" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="postponement_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Motivo de la Postergación
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea id="postponement_reason" 
                                      name="postponement_reason" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none @error('postponement_reason') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                      required
                                      placeholder="Ej: Conflicto de horarios, mantenimiento, etc."></textarea>
                            @error('postponement_reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="suggested_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha Sugerida
                                <span class="text-gray-400 font-normal">(opcional)</span>
                            </label>
                            <input type="date" 
                                   id="suggested_date" 
                                   name="suggested_date" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>
                        
                        <div>
                            <label for="postpone_admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notas del Administrador
                                <span class="text-gray-400 font-normal">(opcional)</span>
                            </label>
                            <textarea id="postpone_admin_notes" 
                                      name="admin_notes" 
                                      rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none"
                                      placeholder="Agregar notas adicionales..."></textarea>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex space-x-3 mt-6">
                        <button type="button" 
                                onclick="closePostponeModal()"
                                class="flex-1 px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Posponer Visita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openApproveModal(visitId, visitorName, visitDate) {
    document.getElementById('approveVisitorName').textContent = visitorName;
    document.getElementById('approveVisitDate').textContent = visitDate;
    document.getElementById('approveForm').action = `/admin/visits/${visitId}/approve`;
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
    document.getElementById('approveForm').reset();
}

function openPostponeModal(visitId, visitorName, visitDate) {
    document.getElementById('postponeVisitorName').textContent = visitorName;
    document.getElementById('postponeVisitDate').textContent = visitDate;
    document.getElementById('postponeForm').action = `/admin/visits/${visitId}/postpone`;
    document.getElementById('postponeModal').classList.remove('hidden');
}

function closePostponeModal() {
    document.getElementById('postponeModal').classList.add('hidden');
    document.getElementById('postponeForm').reset();
}

// Cerrar modales al hacer clic fuera
window.onclick = function(event) {
    const approveModal = document.getElementById('approveModal');
    const postponeModal = document.getElementById('postponeModal');
    
    if (event.target === approveModal) {
        closeApproveModal();
    }
    if (event.target === postponeModal) {
        closePostponeModal();
    }
}
</script>
@endpush