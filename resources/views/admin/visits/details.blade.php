@extends('layouts.app')

@section('title', 'Detalles de Visita #' . $visit->id . ' - Sistema de Visitas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <svg class="h-8 w-8 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Detalles de Visita #{{ $visit->id }}
            </h1>
            <p class="mt-1 text-sm text-gray-500">Información completa de la solicitud de visita</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.visits.all') }}" class="btn-outline">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Información Principal - Lado a Lado -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Información de la Visita -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Información de la Visita
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Detalles Básicos -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Detalles Básicos</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">ID:</span>
                                    <span class="text-sm font-semibold text-gray-900">#{{ $visit->id }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Estado:</span>
                                    <div>
                                        @switch($visit->status)
                                            @case('pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Pendiente
                                                </span>
                                                @break
                                            @case('approved')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Aprobada
                                                </span>
                                                @break
                                            @case('completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                                                    </svg>
                                                    Completada
                                                </span>
                                                @break
                                            @case('postponed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Pospuesta
                                                </span>
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Fecha de Visita:</span>
                                    <span class="text-sm text-gray-900">{{ $visit->preferred_date->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Hora:</span>
                                    <span class="text-sm text-gray-900">{{ $visit->preferred_start_time->format('H:i') }} - {{ $visit->preferred_end_time->format('H:i') }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Participantes Esperados:</span>
                                    <span class="text-sm text-gray-900">{{ $visit->expected_participants ?? 'No especificado' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm font-medium text-gray-600">Servicio de Restaurante:</span>
                                    <span class="text-sm text-gray-900">{{ $visit->restaurant_service ? 'Sí' : 'No' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Propósito y Descripción -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Propósito y Descripción</h4>
                            <div class="space-y-4">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900 mb-2">Propósito:</h5>
                                    <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $visit->visit_purpose ?? 'No especificado' }}</p>
                                </div>
                                
                                @if($visit->institution_description)
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-900 mb-2">Descripción de la Institución:</h5>
                                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $visit->institution_description }}</p>
                                    </div>
                                @endif
                                
                                @if($visit->other_activities)
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-900 mb-2">Otras Actividades:</h5>
                                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $visit->other_activities }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Visitante -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Información del Visitante
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Información Personal -->
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">{{ $visit->user->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $visit->user->email }}</p>
                    </div>
                    
                    <!-- Detalles de Contacto -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Teléfono</p>
                                <p class="text-sm text-gray-500">{{ $visit->user->phone }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Institución</p>
                                <p class="text-sm text-gray-500">{{ $visit->user->institution_name }}</p>
                                <p class="text-xs text-gray-400">{{ ucfirst($visit->user->institution_type) }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Registrado</p>
                                <p class="text-sm text-gray-500">{{ $visit->user->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="h-4 w-4 text-gray-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Acciones
                        </h4>
                        @if($visit->status == 'pending')
                            <a href="{{ route('admin.visits.pending') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Gestionar en Solicitudes Pendientes
                            </a>
                        @elseif($visit->status == 'approved')
                            <a href="{{ route('admin.visits.approved') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                                </svg>
                                Gestionar en Visitas Aprobadas
                            </a>
                        @else
                            <div class="text-center">
                                <p class="text-sm text-gray-500">No hay acciones disponibles para este estado.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Historial de Estados -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="h-4 w-4 text-purple-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Historial de Estados
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                                <div class="ml-4">
                                    <h5 class="text-sm font-semibold text-gray-900">Solicitud Creada</h5>
                                    <p class="text-sm text-gray-500">{{ $visit->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            
                            @if($visit->approved_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                                <div class="ml-4">
                                    <h5 class="text-sm font-semibold text-gray-900">Visita Aprobada</h5>
                                    <p class="text-sm text-gray-500">{{ $visit->approved_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($visit->status == 'postponed')
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-3 h-3 bg-orange-500 rounded-full mt-2"></div>
                                <div class="ml-4">
                                    <h5 class="text-sm font-semibold text-gray-900">Visita Pospuesta</h5>
                                    <p class="text-sm text-gray-500">Estado actual</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($visit->completed_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                                <div class="ml-4">
                                    <h5 class="text-sm font-semibold text-gray-900">Visita Completada</h5>
                                    <p class="text-sm text-gray-500">{{ $visit->completed_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actividades -->
            @if($visit->activities->count() > 0)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Actividades Solicitadas
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($visit->activities as $activity)
                        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-indigo-900 mb-1">{{ $activity->name }}</h4>
                                    <p class="text-sm text-indigo-700 mb-2">{{ $activity->description }}</p>
                                    <div class="flex items-center text-xs text-indigo-600">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Duración: {{ $activity->duration }} minutos
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

                         <!-- Requerimientos Especiales -->
             @if($visit->special_requirements)
             <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                 <div class="px-6 py-4 border-b border-gray-200">
                     <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                         <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                         </svg>
                         Requerimientos Especiales
                     </h3>
                 </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            // Dividir los requerimientos por líneas y crear cuadritos
                            $requirements = explode("\n", $visit->special_requirements);
                            $currentActivity = '';
                            $requirementsList = [];
                            
                            foreach($requirements as $line) {
                                $line = trim($line);
                                if(empty($line)) continue;
                                
                                // Si la línea termina con ":" es un título de actividad
                                if(str_ends_with($line, ':')) {
                                    $currentActivity = $line;
                                } else {
                                    // Es un requerimiento
                                    if($currentActivity) {
                                        $requirementsList[] = [
                                            'activity' => $currentActivity,
                                            'requirement' => $line
                                        ];
                                    } else {
                                        $requirementsList[] = [
                                            'activity' => 'Requerimientos Generales',
                                            'requirement' => $line
                                        ];
                                    }
                                }
                            }
                            
                            // Si no se encontraron actividades, tratar cada línea como requerimiento individual
                            if(empty($requirementsList)) {
                                foreach($requirements as $line) {
                                    $line = trim($line);
                                    if(!empty($line)) {
                                        $requirementsList[] = [
                                            'activity' => 'Requerimientos Generales',
                                            'requirement' => $line
                                        ];
                                    }
                                }
                            }
                        @endphp
                        
                        @if(!empty($requirementsList))
                            @php $lastActivity = ''; @endphp
                            @foreach($requirementsList as $item)
                                @if($item['activity'] !== $lastActivity)
                                    @if($lastActivity !== '')
                                        </div>
                                    @endif
                                                                         <div class="mb-4">
                                         <h4 class="text-sm font-semibold text-blue-600 uppercase tracking-wide mb-3 flex items-center">
                                             <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                             </svg>
                                             {{ $item['activity'] }}
                                         </h4>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                @endif
                                
                                <div class="inline-flex items-center px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="truncate">{{ $item['requirement'] }}</span>
                                </div>
                                
                                @php $lastActivity = $item['activity']; @endphp
                            @endforeach
                            @if($lastActivity !== '')
                                </div>
                            @endif
                        @else
                            <div class="text-sm text-gray-500 italic">No hay requerimientos especiales especificados</div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Horarios Confirmados -->
            @if($visit->confirmed_date)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="h-5 w-5 text-purple-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Horarios Confirmados
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Fecha Confirmada:</span>
                                <span class="text-sm text-gray-900">{{ $visit->confirmed_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Hora de Inicio:</span>
                                <span class="text-sm text-gray-900">{{ $visit->confirmed_start_time ? $visit->confirmed_start_time->format('H:i') : 'No confirmada' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-600">Hora de Fin:</span>
                                <span class="text-sm text-gray-900">{{ $visit->confirmed_end_time ? $visit->confirmed_end_time->format('H:i') : 'No confirmada' }}</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Fecha Solicitada:</span>
                                <span class="text-sm text-gray-900">{{ $visit->preferred_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Hora Solicitada:</span>
                                <span class="text-sm text-gray-900">{{ $visit->preferred_start_time->format('H:i') }} - {{ $visit->preferred_end_time->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Asistentes -->
            @if($visit->attendees->count() > 0)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="h-5 w-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        Asistentes
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($visit->attendees as $attendee)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $attendee->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendee->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendee->phone }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendee->role }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Contenido adicional si es necesario -->
            </div>
            
            <div class="space-y-6">

            <!-- Notas del Administrador -->
            @if($visit->admin_notes || $visit->postponement_reason || $visit->completion_notes)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="h-5 w-5 text-yellow-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Notas del Administrador
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($visit->admin_notes)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Notas Generales:</h4>
                            <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $visit->admin_notes }}</p>
                        </div>
                    @endif
                    
                    @if($visit->rejection_reason)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Motivo del Rechazo:</h4>
                            <p class="text-sm text-red-600 bg-red-50 p-3 rounded-lg">{{ $visit->rejection_reason }}</p>
                        </div>
                    @endif
                    
                    @if($visit->completion_notes)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Notas de Completado:</h4>
                            <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $visit->completion_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
            </div>
        </div>
    </div>
</div>
@endsection