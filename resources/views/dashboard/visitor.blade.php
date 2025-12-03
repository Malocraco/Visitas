@extends('layouts.app')

@section('title', 'Dashboard - Visitante')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                ¡Bienvenido, {{ $user->name }}!
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Gestiona tus solicitudes de visita y mantén comunicación con los administradores.
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <a href="{{ route('visits.request') }}" class="btn-primary">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nueva Solicitud
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Visits -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Solicitudes</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $visits->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Visits -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-warning-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-warning-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pendientes</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $visits->where('status', 'pending')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Visits -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-success-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-success-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Aprobadas</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $visits->where('status', 'approved')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Visits -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Completadas</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $visits->where('status', 'completed')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Visits -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-medium text-gray-900">Solicitudes Recientes</h3>
        </div>
        <div class="card-body">
            @if($visits->count() > 0)
                <div class="flow-root">
                    <ul role="list" class="-my-5 divide-y divide-gray-200">
                        @foreach($visits->take(5) as $visit)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        @switch($visit->status)
                                            @case('pending')
                                                <span class="inline-flex items-center rounded-full bg-warning-100 px-2.5 py-0.5 text-xs font-medium text-warning-800">
                                                    Pendiente
                                                </span>
                                                @break
                                            @case('approved')
                                                <span class="inline-flex items-center rounded-full bg-success-100 px-2.5 py-0.5 text-xs font-medium text-success-800">
                                                    Aprobada
                                                </span>
                                                @break
                                            @case('rejected')
                                                <span class="inline-flex items-center rounded-full bg-danger-100 px-2.5 py-0.5 text-xs font-medium text-danger-800">
                                                    Rechazada
                                                </span>
                                                @break
                                            @case('completed')
                                                <span class="inline-flex items-center rounded-full bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800">
                                                    Completada
                                                </span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center rounded-full bg-secondary-100 px-2.5 py-0.5 text-xs font-medium text-secondary-800">
                                                    {{ ucfirst($visit->status) }}
                                                </span>
                                        @endswitch
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium text-gray-900">
                                            Visita para {{ $visit->institution_name }}
                                        </p>
                                        <p class="truncate text-sm text-gray-500">
                                            {{ $visit->preferred_date->format('d/m/Y') }} - {{ $visit->expected_participants }} participantes
                                        </p>
                                    </div>
                                    <div>
                                        <a href="{{ route('visits.show', $visit->id) }}" class="inline-flex items-center rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                            Ver detalles
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="mt-6">
                    <a href="{{ route('visits.my-requests') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Ver todas las solicitudes
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay solicitudes</h3>
                    <p class="mt-1 text-sm text-gray-500">Comienza creando tu primera solicitud de visita.</p>
                    <div class="mt-6">
                        <a href="{{ route('visits.request') }}" class="btn-primary">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Nueva Solicitud
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="mx-auto h-12 w-12 bg-primary-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nueva Solicitud</h3>
                <p class="text-sm text-gray-500 mb-4">Solicita una nueva visita a nuestra institución</p>
                <a href="{{ route('visits.request') }}" class="btn-primary w-full">Crear Solicitud</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center">
                <div class="mx-auto h-12 w-12 bg-success-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-success-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Mis Solicitudes</h3>
                <p class="text-sm text-gray-500 mb-4">Revisa el estado de tus solicitudes</p>
                <a href="{{ route('visits.my-requests') }}" class="btn-outline w-full">Ver Solicitudes</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center">
                <div class="mx-auto h-12 w-12 bg-warning-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-warning-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Chat</h3>
                <p class="text-sm text-gray-500 mb-4">Comunícate con los administradores</p>
                <a href="{{ route('chat.index') }}" class="btn-outline w-full">Abrir Chat</a>
            </div>
        </div>
    </div>
</div>
@endsection
