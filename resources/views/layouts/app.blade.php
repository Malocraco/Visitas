<!DOCTYPE html>
<html lang="es" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Visitas')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="h-full bg-white">
    <div class="min-h-full">
            @auth
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0" id="sidebar">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 bg-primary-green">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <div class="h-8 w-8 bg-white rounded-lg flex items-center justify-center">
                        <svg class="h-5 w-5 text-primary-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white">Sistema de Visitas</span>
                </a>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-5 px-2">
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard*') ? 'bg-primary-green/10 text-primary-green' : 'text-gray-600 hover:bg-primary-green/10 hover:text-primary-green' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                        </svg>
                        Dashboard
                    </a>
                    
                    @if(auth()->user()->isVisitor())
                        <!-- Nueva Solicitud -->
                        <a href="{{ route('visits.request') }}" class="sidebar-link {{ request()->routeIs('visits.request') ? 'bg-primary-green/10 text-primary-green' : 'text-gray-600 hover:bg-primary-green/10 hover:text-primary-green' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Nueva Solicitud
                        </a>
                        
                        <!-- Mis Solicitudes -->
                        <a href="{{ route('visits.my-requests') }}" class="sidebar-link {{ request()->routeIs('visits.my-requests') ? 'bg-primary-green/10 text-primary-green' : 'text-gray-600 hover:bg-primary-green/10 hover:text-primary-green' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Mis Solicitudes
                        </a>
                            @endif
                    
                    @if(auth()->user()->isSuperAdmin())
                        @php
                            $isVisitsSection = request()->routeIs('admin.visits.*');
                            $isUsersSection = request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*');
                            $isCommsSection = request()->routeIs('chat.*');
                        @endphp
                        <!-- Sección: Gestión de Visitas -->
                        <div class="mt-6">
                            <button type="button" 
                                    class="w-full flex items-center justify-between px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:bg-primary-green/10 rounded-md transition-colors"
                                    onclick="toggleSection('visits-section')">
                                <span>Gestión de Visitas</span>
                                <svg class="h-4 w-4 transition-transform duration-200" id="visits-section-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="mt-2 space-y-1 {{ $isVisitsSection ? '' : 'hidden' }}" id="visits-section">
                                <a href="{{ route('admin.visits.pending') }}" class="sidebar-link pl-8 {{ request()->routeIs('admin.visits.pending') ? 'bg-primary-green/10 text-primary-green' : 'text-gray-600 hover:bg-primary-green/10 hover:text-primary-green' }}">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Solicitudes Pendientes
                                </a>
                                <a href="{{ route('admin.visits.all') }}" class="sidebar-link pl-8 {{ request()->routeIs('admin.visits.all') ? 'bg-primary-green/10 text-primary-green' : 'text-gray-600 hover:bg-primary-green/10 hover:text-primary-green' }}">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Todas las Visitas
                                </a>
                                <a href="{{ route('admin.visits.approved') }}" class="sidebar-link pl-8 {{ request()->routeIs('admin.visits.approved') ? 'bg-primary-green/10 text-primary-green' : 'text-gray-600 hover:bg-primary-green/10 hover:text-primary-green' }}">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Visitas Aprobadas
                                </a>
                                <a href="{{ route('admin.visits.calendar') }}" class="sidebar-link pl-8 {{ request()->routeIs('admin.visits.calendar') ? 'bg-primary-green/10 text-primary-green' : 'text-gray-600 hover:bg-primary-green/10 hover:text-primary-green' }}">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Calendario
                                </a>
                                <a href="{{ route('admin.visits.reports') }}" class="sidebar-link pl-8 {{ request()->routeIs('admin.visits.reports') ? 'bg-primary-green/10 text-primary-green' : 'text-gray-600 hover:bg-primary-green/10 hover:text-primary-green' }}">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                    </svg>
                                    Reportes
                                </a>
                            </div>
                        </div>

                        <!-- Sección: Gestión de Usuarios -->
                        <div class="mt-6">
                            <button type="button" 
                                    class="w-full flex items-center justify-between px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:bg-primary-green/10 rounded-md transition-colors"
                                    onclick="toggleSection('users-section')">
                                <span>Gestión de Usuarios</span>
                                <svg class="h-4 w-4 transition-transform duration-200" id="users-section-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="mt-2 space-y-1 {{ $isUsersSection ? '' : 'hidden' }}" id="users-section">
                                <a href="{{ route('admin.users.index') }}" class="sidebar-link pl-8 {{ request()->routeIs('admin.users.*') ? 'bg-primary-green/10 text-primary-green' : 'text-gray-600 hover:bg-primary-green/10 hover:text-primary-green' }}">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                    </svg>
                                    Usuarios
                                </a>
                                <a href="{{ route('admin.roles.index') }}" class="sidebar-link pl-8 {{ request()->routeIs('admin.roles.*') ? 'bg-primary-green/10 text-primary-green' : 'text-gray-600 hover:bg-primary-green/10 hover:text-primary-green' }}">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    </svg>
                                    Roles y Permisos
                                </a>
                            </div>
                        </div>

                        <!-- Sección: Comunicación -->
                        <div class="mt-6">
                            <button type="button" 
                                    class="w-full flex items-center justify-between px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:bg-primary-green/10 rounded-md transition-colors"
                                    onclick="toggleSection('communication-section')">
                                <span>Comunicación</span>
                                <svg class="h-4 w-4 transition-transform duration-200" id="communication-section-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="mt-2 space-y-1 {{ $isCommsSection ? '' : 'hidden' }}" id="communication-section">
                                <a href="{{ route('chat.index') }}" class="sidebar-link pl-8 {{ request()->routeIs('chat.*') ? 'bg-primary-green/10 text-primary-green' : 'text-gray-600 hover:bg-primary-green/10 hover:text-primary-green' }}">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    Chat
                                    <span id="chat-notifications" class="ml-auto inline-flex items-center rounded-full bg-danger-100 px-2 py-0.5 text-xs font-medium text-danger-800 hidden">0</span>
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    @if(auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())
                        <!-- Visitas Aprobadas -->
                        <a href="{{ route('admin.visits.approved') }}" class="sidebar-link {{ request()->routeIs('admin.visits.approved') ? 'bg-primary-green/10 text-primary-green' : 'text-gray-600 hover:bg-primary-green/10 hover:text-primary-green' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Visitas Aprobadas
                        </a>
                    @endif
                    
                    @if(!auth()->user()->isSuperAdmin())
                        <!-- Chat -->
                        <a href="{{ route('chat.index') }}" class="sidebar-link {{ request()->routeIs('chat.*') ? 'bg-primary-green/10 text-primary-green' : 'text-gray-600 hover:bg-primary-green/10 hover:text-primary-green' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Chat
                            <span id="chat-notifications" class="ml-auto inline-flex items-center rounded-full bg-danger-100 px-2 py-0.5 text-xs font-medium text-danger-800 hidden">0</span>
                        </a>
                    @endif
                </div>
            </nav>
            
            <!-- User info at bottom -->
            <div class="absolute bottom-0 w-full p-4 border-t border-primary-green/20">
                <div class="flex items-center">
                    <div class="h-8 w-8 rounded-full bg-primary-green flex items-center justify-center">
                        <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->getPrimaryRole()->display_name ?? 'Usuario' }}</p>
                    </div>
                    <div class="relative">
                        <button type="button" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none" id="user-menu-button">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div class="absolute right-0 bottom-full mb-2 w-48 origin-bottom-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" id="user-menu">
                            <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                <svg class="inline-block w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Configuración
                            </a>
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                    <svg class="inline-block w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile sidebar overlay -->
        <div class="fixed inset-0 z-40 bg-primary-green/20 hidden" id="sidebar-overlay"></div>
        @endauth
        
        <!-- Main content -->
        <div class="{{ auth()->check() ? 'lg:pl-64' : '' }}">
            @auth
            <!-- Top bar -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-primary-green/20 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <!-- Mobile menu button -->
                <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" id="mobile-menu-button">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                
                <!-- Separator -->
                <div class="h-6 w-px bg-primary-green/20 lg:hidden" aria-hidden="true"></div>
                
                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="flex flex-1"></div>
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Notifications -->
                        <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500">
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                        </button>
                    </div>
                </div>
        </div>
    @endauth
            
            <!-- Page content -->
            <main class="py-6">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <!-- Flash Messages -->
                @if(session('success'))
                        <div class="mb-4 rounded-md bg-success-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-success-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-success-800">{{ session('success') }}</p>
                                </div>
                            </div>
                    </div>
                @endif
                
                @if(session('error'))
                        <div class="mb-4 rounded-md bg-danger-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-danger-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-danger-800">{{ session('error') }}</p>
                                </div>
                            </div>
                    </div>
                @endif
                
                @if($errors->any())
                        <div class="mb-4 rounded-md bg-danger-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-danger-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm text-danger-800">
                                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                                    </div>
                                </div>
                            </div>
                    </div>
                @endif

                    @yield('content')
            </div>
            </main>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');
            
            // Mobile sidebar toggle
            if (mobileMenuButton && sidebar && sidebarOverlay) {
                mobileMenuButton.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                    sidebarOverlay.classList.toggle('hidden');
                });
                
                // Close sidebar when clicking overlay
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                });
            }
            
            // User menu toggle
            if (userMenuButton && userMenu) {
                userMenuButton.addEventListener('click', function() {
                    userMenu.classList.toggle('hidden');
                });
                
                // Close user menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }

            // Función para manejar secciones colapsables
            window.toggleSection = function(sectionId) {
                const section = document.getElementById(sectionId);
                const icon = document.getElementById(sectionId + '-icon');
                
                if (section && icon) {
                    section.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                }
            };

            // Auto-expandir secciones según la ruta actual al cargar
            @if(auth()->user()->isSuperAdmin())
                @php
                    $isVisitsSection = request()->routeIs('admin.visits.*');
                    $isUsersSection = request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*');
                    $isCommsSection = request()->routeIs('chat.*');
                @endphp
                @if($isVisitsSection)
                    // Expandir sección de visitas
                    const visitsSection = document.getElementById('visits-section');
                    const visitsIcon = document.getElementById('visits-section-icon');
                    if (visitsSection) visitsSection.classList.remove('hidden');
                    if (visitsIcon) visitsIcon.classList.add('rotate-180');
                @endif
                @if($isUsersSection)
                    // Expandir sección de usuarios
                    const usersSection = document.getElementById('users-section');
                    const usersIcon = document.getElementById('users-section-icon');
                    if (usersSection) usersSection.classList.remove('hidden');
                    if (usersIcon) usersIcon.classList.add('rotate-180');
                @endif
                @if($isCommsSection)
                    // Expandir sección de comunicación
                    const commsSection = document.getElementById('communication-section');
                    const commsIcon = document.getElementById('communication-section-icon');
                    if (commsSection) commsSection.classList.remove('hidden');
                    if (commsIcon) commsIcon.classList.add('rotate-180');
                @endif
            @endif
        });
    </script>

@stack('scripts')
<!-- Chat Notifications Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para actualizar notificaciones de chat
    function updateChatNotifications() {
        fetch('/chat/notifications')
            .then(response => response.json())
            .then(data => {
                const notificationElement = document.getElementById('chat-notifications');
                if (data.total_notifications > 0) {
                    notificationElement.textContent = data.total_notifications;
                    notificationElement.classList.remove('hidden');
                } else {
                    notificationElement.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error al obtener notificaciones de chat:', error);
            });
    }

    // Actualizar notificaciones al cargar la página
    updateChatNotifications();

    // Actualizar notificaciones cada 30 segundos
    setInterval(updateChatNotifications, 30000);

    // Actualizar notificaciones cuando se hace clic en el enlace de chat
    document.querySelector('a[href="{{ route('chat.index') }}"]').addEventListener('click', function() {
        setTimeout(updateChatNotifications, 1000);
    });
});
</script>

</body>
</html>