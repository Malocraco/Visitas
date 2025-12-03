@extends('layouts.app')

@section('title', 'Detalles de Solicitud - Sistema de Visitas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Detalles de la Solicitud de Visita
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Información completa de tu solicitud de visita
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <a href="{{ route('visits.my-requests') }}" class="btn-outline">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Estado de la solicitud -->
    <div class="rounded-md bg-primary-50 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-primary-800">Estado de la Solicitud</h3>
                <div class="mt-2">
                    @switch($visit->status)
                        @case('pending')
                            <span class="badge-warning">
                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pendiente de Revisión
                            </span>
                            <p class="mt-1 text-sm text-primary-700">Tu solicitud está siendo revisada por nuestros administradores.</p>
                            @break
                        @case('approved')
                            <span class="badge-success">
                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Aprobada
                            </span>
                            <p class="mt-1 text-sm text-primary-700">¡Tu solicitud ha sido aprobada! Prepárate para tu visita.</p>
                            @break
                        @case('rejected')
                            <span class="badge-danger">
                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Rechazada
                            </span>
                            <p class="mt-1 text-sm text-primary-700">Tu solicitud no pudo ser aprobada. Revisa los detalles.</p>
                            @break
                        @case('completed')
                            <span class="badge-primary">
                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Completada
                            </span>
                            <p class="mt-1 text-sm text-primary-700">La visita se ha completado exitosamente.</p>
                            @break
                    @endswitch
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Información General -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="mr-2 h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Información General
                </h3>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fecha</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($visit->preferred_date)->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Hora</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($visit->preferred_start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($visit->preferred_end_time)->format('H:i') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Propósito</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $visit->visit_purpose }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Solicitado</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($visit->created_at)->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Información de Contacto -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="mr-2 h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Información de Contacto
                </h3>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Contacto</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $visit->contact_person }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $visit->contact_phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $visit->contact_email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Institución</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $visit->institution_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($visit->institution_type) }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Actividades Seleccionadas -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="mr-2 h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Actividades Seleccionadas
                </h3>
            </div>
            <div class="card-body">
                @if($visit->activities->count() > 0)
                    <div class="space-y-4">
                        @foreach($visit->activities as $activity)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-success-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $activity->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $activity->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">No se seleccionaron actividades específicas.</p>
                @endif
            </div>
        </div>

        <!-- Servicios Adicionales -->
        @if($visit->restaurant_service)
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="mr-2 h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" />
                        </svg>
                        Servicios Adicionales
                    </h3>
                </div>
                <div class="card-body">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Restaurante</dt>
                            <dd class="mt-1">
                                <span class="badge-success">Solicitado</span>
                            </dd>
                        </div>
                        @if($visit->restaurant_participants)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Personas</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $visit->restaurant_participants }} personas</dd>
                            </div>
                        @endif
                        @if($visit->restaurant_notes)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Notas</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $visit->restaurant_notes }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        @endif

    </div>

    <!-- Requisitos y Actividades -->
    @if($visit->special_requirements || $visit->other_activities)
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="mr-2 h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Requisitos y Actividades
                </h3>
            </div>
            <div class="card-body">
                @if($visit->special_requirements)
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-4">Requisitos Obligatorios</h4>
                        <div class="space-y-4">
                            @php
                                // Dividir los requisitos por actividad
                                $requirements = explode('📋', $visit->special_requirements);
                                array_shift($requirements); // Remover el primer elemento vacío
                            @endphp
                            
                            @foreach($requirements as $requirement)
                                @php
                                    $lines = explode("\n", trim($requirement));
                                    $activityName = trim($lines[0], ':');
                                    $activityRequirements = array_slice($lines, 1);
                                @endphp
                                
                                <div class="bg-primary-50 border border-primary-200 rounded-lg p-4">
                                    <div class="flex items-center mb-3">
                                        <svg class="h-5 w-5 text-primary-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <h5 class="text-sm font-medium text-primary-900">{{ $activityName }}</h5>
                                    </div>
                                    <ul class="space-y-2">
                                        @foreach($activityRequirements as $req)
                                            @if(trim($req) !== '')
                                                <li class="flex items-start">
                                                    <svg class="h-4 w-4 text-primary-500 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                                    </svg>
                                                    <span class="text-sm text-primary-800">{{ trim($req, '• ') }}</span>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if($visit->other_activities)
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Otras Actividades</h4>
                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-4">{{ $visit->other_activities }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
