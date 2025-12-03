@extends('layouts.app')

@section('title', 'Dashboard - Administrador')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                ¡Bienvenido, Administrador!
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Gestiona las visitas aprobadas y coordina las actividades.
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 space-x-3">
            <a href="{{ route('admin.visits.approved') }}" class="btn-primary">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Visitas Aprobadas
            </a>
            <a href="{{ route('admin.visits.calendar') }}" class="btn-outline">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Calendario
            </a>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="card">
        <div class="card-body text-center py-8">
            <div class="mx-auto h-16 w-16 bg-primary-100 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Panel de Administración</h3>
            <p class="text-sm text-gray-500 max-w-2xl mx-auto">
                Revisa las visitas aprobadas por el SuperAdministrador, coordina las actividades, 
                gestiona el calendario y mantén comunicación con los visitantes.
            </p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Approved Visits -->
        <div class="card hover:shadow-medium transition-shadow duration-200">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Visitas Aprobadas</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                @php
                                    $approvedVisits = \App\Models\Visit::where('status', 'approved')->count();
                                @endphp
                                {{ $approvedVisits }}
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.visits.approved') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                        Ver detalles
                        <svg class="inline-block ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Completed Visits -->
        <div class="card hover:shadow-medium transition-shadow duration-200">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-success-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-success-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Visitas Completadas</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                @php
                                    $completedVisits = \App\Models\Visit::where('status', 'completed')->count();
                                @endphp
                                {{ $completedVisits }}
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.visits.approved') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                        Ver detalles
                        <svg class="inline-block ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Today's Visits -->
        <div class="card hover:shadow-medium transition-shadow duration-200">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-warning-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-warning-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Visitas Hoy</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                @php
                                    $todayVisits = \App\Models\Visit::where('status', 'approved')
                                        ->whereDate('confirmed_date', today())
                                        ->count();
                                @endphp
                                {{ $todayVisits }}
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.visits.calendar') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                        Ver calendario
                        <svg class="inline-block ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Active Chats -->
        <div class="card hover:shadow-medium transition-shadow duration-200">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-danger-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Chats Activos</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                @php
                                    $openChats = \App\Models\ChatRoom::where('status', 'open')->count();
                                @endphp
                                {{ $openChats }}
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('chat.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                        Ver chats
                        <svg class="inline-block ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Assigned Visits -->
    @if($assignedVisits->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-medium text-gray-900">Visitas Asignadas Recientes</h3>
        </div>
        <div class="card-body">
            <div class="flow-root">
                <ul role="list" class="-my-5 divide-y divide-gray-200">
                    @foreach($assignedVisits->take(5) as $visit)
                        <li class="py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center rounded-full bg-success-100 px-2.5 py-0.5 text-xs font-medium text-success-800">
                                        Aprobada
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-gray-900">
                                        {{ $visit->institution_name }}
                                    </p>
                                    <p class="truncate text-sm text-gray-500">
                                        {{ $visit->user->name }} • {{ $visit->confirmed_date ? $visit->confirmed_date->format('d/m/Y') : $visit->preferred_date->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div>
                                    <a href="{{ route('admin.visits.details', $visit->id) }}" class="inline-flex items-center rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                        Ver detalles
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-6">
                <a href="{{ route('admin.visits.approved') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Ver todas las visitas aprobadas
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="mx-auto h-12 w-12 bg-primary-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Visitas Aprobadas</h3>
                <p class="text-sm text-gray-500 mb-4">Gestiona las visitas que han sido aprobadas</p>
                <a href="{{ route('admin.visits.approved') }}" class="btn-primary w-full">Ver Visitas</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center">
                <div class="mx-auto h-12 w-12 bg-warning-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-warning-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Calendario</h3>
                <p class="text-sm text-gray-500 mb-4">Visualiza y gestiona el calendario de visitas</p>
                <a href="{{ route('admin.visits.calendar') }}" class="btn-outline w-full">Ver Calendario</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center">
                <div class="mx-auto h-12 w-12 bg-danger-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Chat</h3>
                <p class="text-sm text-gray-500 mb-4">Comunícate con los visitantes</p>
                <a href="{{ route('chat.index') }}" class="btn-outline w-full">Abrir Chat</a>
            </div>
        </div>
    </div>
</div>
@endsection
