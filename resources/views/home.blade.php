<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Sistema de Agendamiento de Visitas</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary-green": "#00875A",
                        "sena-green": "#00875A",
                        "sena-green-dark": "#006B47",
                        "sena-green-light": "#00A66B",
                        "background-light": "#FFFFFF",
                        "background-dark": "#F5F5F5",
                        "card-dark": "#FFFFFF",
                        "border-dark": "#E0E0E0"
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-white font-display text-gray-800">
    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <div class="flex flex-1 justify-center">
                <div class="layout-content-container flex flex-col w-full max-w-6xl px-4 md:px-8">
                    <!-- Header -->
                    <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-primary-green/20 py-4 bg-white">
                        <div class="flex items-center gap-4 text-black">
                            <div class="size-6 text-primary-green">
                                <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2L1 9l4 2.18v6.32L12 22l7-4.5V11.18L23 9 12 2zm0 2.45L19.5 9l-2.75 1.5-4.75-2.6L12 4.45zm-5 4.05L12 11.18v6.32L7 14.5V8.5z"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">Sistema de Visitas</h2>
                        </div>
                        <div class="hidden md:flex flex-1 justify-end items-center gap-4">
                            <div class="flex items-center gap-6">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="text-sm font-medium leading-normal hover:text-primary-green transition-colors">Dashboard</a>
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm font-medium leading-normal hover:text-primary-green transition-colors">
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="text-sm font-medium leading-normal hover:text-primary-green transition-colors">Iniciar Sesión</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="text-sm font-medium leading-normal hover:text-primary-green transition-colors">Registrarse</a>
                                    @endif
                                @endauth
                            </div>
                            @auth
                                <div class="flex gap-2">
                                    <a href="{{ route('dashboard') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary-green text-white text-sm font-bold leading-normal tracking-[0.015em] hover:opacity-90 transition-opacity">
                                        <span class="truncate">Dashboard</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <button class="md:hidden p-2 rounded-lg hover:bg-primary-green/10">
                            <span class="material-symbols-outlined text-black">menu</span>
                        </button>
                    </header>

                    <main>
                        <!-- Hero Section -->
                        <section class="py-16 md:py-24">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                                <div class="flex flex-col gap-6 text-center lg:text-left">
                                    <div class="flex flex-col gap-4">
                                        <h1 class="text-4xl font-black leading-tight tracking-tighter md:text-5xl lg:text-6xl text-gray-900">
                                            Organiza y Gestiona tus Visitas Educativas de Forma Eficiente
                                        </h1>
                                        <h2 class="text-lg font-normal leading-normal text-gray-700">
                                            Nuestra plataforma simplifica el proceso de programación, gestión y experiencia de tours educativos en nuestra institución.
                                        </h2>
                                    </div>
                                    <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                                        @auth
                                            <a href="{{ route('dashboard') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary-green text-white text-base font-bold leading-normal tracking-[0.015em] hover:opacity-90 transition-opacity">
                                                <span class="truncate">Ir al Dashboard</span>
                                            </a>
                                        @else
                                            <a href="{{ route('register') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary-green text-white text-base font-bold leading-normal tracking-[0.015em] hover:opacity-90 transition-opacity">
                                                <span class="truncate">Solicitar Visita</span>
                                            </a>
                                            <a href="{{ route('register') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-white border-2 border-primary-green text-primary-green text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary-green hover:text-white transition-colors">
                                                <span class="truncate">Registrar Institución</span>
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                                <div class="relative w-full aspect-square md:aspect-video lg:aspect-square group">
                                    <!-- Decorative elements -->
                                    <div class="absolute -top-4 -right-4 w-32 h-32 bg-primary-green/10 rounded-full blur-2xl z-0"></div>
                                    <div class="absolute -bottom-4 -left-4 w-40 h-40 bg-primary-green/5 rounded-full blur-3xl z-0"></div>
                                    
                                    <!-- Main image container -->
                                    <div class="relative w-full h-full rounded-2xl overflow-hidden shadow-2xl border-4 border-white ring-4 ring-primary-green/20 transform transition-all duration-300 group-hover:scale-[1.02] group-hover:shadow-3xl">
                                        <!-- Image with overlay -->
                                        <div class="relative w-full h-full">
                                            @if(file_exists(public_path('images/image.png')))
                                                <img src="{{ asset('images/image.png') }}" 
                                                     alt="Centro de Formación Agroindustrial La Angostura - SENA" 
                                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            @elseif(file_exists(public_path('images/sena-edificio.jpg')))
                                                <img src="{{ asset('images/sena-edificio.jpg') }}" 
                                                     alt="Centro de Formación Agroindustrial La Angostura - SENA" 
                                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-primary-green/60 bg-gradient-to-br from-primary-green/5 to-primary-green/10">
                                                    <p class="text-sm">Imagen del edificio SENA</p>
                                                </div>
                                            @endif
                                            
                                            <!-- Subtle gradient overlay -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-primary-green/5 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                            
                                            <!-- Corner accent -->
                                            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-primary-green/20 to-transparent rounded-bl-full"></div>
                                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-primary-green/20 to-transparent rounded-tr-full"></div>
                                        </div>
                                        
                                        <!-- Frame decoration -->
                                        <div class="absolute inset-0 border-2 border-white/50 rounded-2xl pointer-events-none"></div>
                                    </div>
                                    
                                    <!-- Floating badge -->
                                    <div class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 bg-white px-6 py-2 rounded-full shadow-lg border-2 border-primary-green/30">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 bg-primary-green rounded-full animate-pulse"></div>
                                            <span class="text-xs font-semibold text-primary-green">Centro de Formación Agroindustrial</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Features Section -->
                        <section class="py-16 md:py-24">
                            <div class="flex flex-col gap-12">
                                <div class="flex flex-col gap-4 text-center max-w-2xl mx-auto">
                                    <h2 class="text-3xl font-bold leading-tight tracking-tight md:text-4xl text-gray-900">
                                        ¿Por Qué Elegir Nuestra Plataforma?
                                    </h2>
                                    <p class="text-base font-normal leading-normal text-gray-700">
                                        Descubre los beneficios clave que hacen que programar visitas sea más fácil y eficiente para todos los involucrados.
                                    </p>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <div class="flex flex-col gap-3 rounded-xl border border-primary-green/20 bg-white p-6 transition-all hover:shadow-lg hover:border-primary-green">
                                        <div class="flex items-center justify-center size-12 rounded-lg bg-primary-green/20 text-primary-green">
                                            <span class="material-symbols-outlined">dashboard</span>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <h3 class="text-lg font-bold leading-tight text-gray-900">Gestión Centralizada</h3>
                                            <p class="text-sm font-normal leading-normal text-gray-500">Gestiona todas las solicitudes de visita desde un panel intuitivo.</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-3 rounded-xl border border-primary-green/20 bg-white p-6 transition-all hover:shadow-lg hover:border-primary-green">
                                        <div class="flex items-center justify-center size-12 rounded-lg bg-primary-green/20 text-primary-green">
                                            <span class="material-symbols-outlined">calendar_month</span>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <h3 class="text-lg font-bold leading-tight text-gray-900">Calendario Interactivo</h3>
                                            <p class="text-sm font-normal leading-normal text-gray-500">Visualiza disponibilidad y programa visitas con un calendario fácil de usar.</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-3 rounded-xl border border-primary-green/20 bg-white p-6 transition-all hover:shadow-lg hover:border-primary-green">
                                        <div class="flex items-center justify-center size-12 rounded-lg bg-primary-green/20 text-primary-green">
                                            <span class="material-symbols-outlined">notifications_active</span>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <h3 class="text-lg font-bold leading-tight text-gray-900">Notificaciones Automáticas</h3>
                                            <p class="text-sm font-normal leading-normal text-gray-500">Mantén a todos informados con actualizaciones automáticas por email y SMS.</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-3 rounded-xl border border-primary-green/20 bg-white p-6 transition-all hover:shadow-lg hover:border-primary-green">
                                        <div class="flex items-center justify-center size-12 rounded-lg bg-primary-green/20 text-primary-green">
                                            <span class="material-symbols-outlined">groups</span>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <h3 class="text-lg font-bold leading-tight text-gray-900">Asignación de Recursos</h3>
                                            <p class="text-sm font-normal leading-normal text-gray-500">Asigna guías y reserva salas para tus tours sin esfuerzo.</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-3 rounded-xl border border-primary-green/20 bg-white p-6 transition-all hover:shadow-lg hover:border-primary-green">
                                        <div class="flex items-center justify-center size-12 rounded-lg bg-primary-green/20 text-primary-green">
                                            <span class="material-symbols-outlined">monitoring</span>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <h3 class="text-lg font-bold leading-tight text-gray-900">Reportes Personalizados</h3>
                                            <p class="text-sm font-normal leading-normal text-gray-500">Genera reportes detallados sobre datos de visitantes y actividades populares.</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-3 rounded-xl border border-primary-green/20 bg-white p-6 transition-all hover:shadow-lg hover:border-primary-green">
                                        <div class="flex items-center justify-center size-12 rounded-lg bg-primary-green/20 text-primary-green">
                                            <span class="material-symbols-outlined">forum</span>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <h3 class="text-lg font-bold leading-tight text-gray-900">Comunicación Fluida</h3>
                                            <p class="text-sm font-normal leading-normal text-gray-500">Comunícate directamente con visitantes a través de un sistema de mensajería integrado.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- How It Works Section -->
                        <section class="py-16 md:py-24">
                            <div class="flex flex-col gap-12 items-center">
                                <div class="flex flex-col gap-4 text-center max-w-2xl mx-auto">
                                    <h2 class="text-3xl font-bold leading-tight tracking-tight md:text-4xl text-gray-900">
                                        Programación en 4 Pasos Simples
                                    </h2>
                                </div>
                                <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                                    <div class="flex flex-col items-center text-center gap-4">
                                        <div class="flex items-center justify-center size-16 rounded-full bg-primary-green/20 text-primary-green font-bold text-xl border-4 border-primary-green">1</div>
                                        <h3 class="font-bold text-lg text-gray-900">Registrarse / Iniciar Sesión</h3>
                                        <p class="text-sm text-gray-500">Crea una cuenta o inicia sesión para acceder al panel de programación.</p>
                                    </div>
                                    <div class="flex flex-col items-center text-center gap-4">
                                        <div class="flex items-center justify-center size-16 rounded-full bg-primary-green/20 text-primary-green font-bold text-xl border-4 border-primary-green">2</div>
                                        <h3 class="font-bold text-lg text-gray-900">Elegir Actividad y Fecha</h3>
                                        <p class="text-sm text-gray-500">Explora actividades disponibles y selecciona una fecha y hora adecuada del calendario.</p>
                                    </div>
                                    <div class="flex flex-col items-center text-center gap-4">
                                        <div class="flex items-center justify-center size-16 rounded-full bg-primary-green/20 text-primary-green font-bold text-xl border-4 border-primary-green">3</div>
                                        <h3 class="font-bold text-lg text-gray-900">Recibir Confirmación</h3>
                                        <p class="text-sm text-gray-500">Recibe una confirmación instantánea con todos los detalles de la visita enviados a tu email.</p>
                                    </div>
                                    <div class="flex flex-col items-center text-center gap-4">
                                        <div class="flex items-center justify-center size-16 rounded-full bg-primary-green/20 text-primary-green font-bold text-xl border-4 border-primary-green">4</div>
                                        <h3 class="font-bold text-lg text-gray-900">Disfrutar tu Visita</h3>
                                        <p class="text-sm text-gray-500">Llega el día programado y disfruta de una experiencia educativa fluida.</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Activities Section -->
                        <section class="py-16 md:py-24">
                            <div class="flex flex-col gap-12">
                                <div class="flex flex-col gap-4 text-center max-w-2xl mx-auto">
                                    <h2 class="text-3xl font-bold leading-tight tracking-tight md:text-4xl text-gray-900">
                                        Explora Nuestras Actividades Educativas
                                    </h2>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <div class="group flex flex-col rounded-xl overflow-hidden bg-white border border-primary-green/20 hover:shadow-lg hover:border-primary-green transition-shadow">
                                        <div class="w-full h-48 bg-gradient-to-br from-primary-green/80 to-primary-green"></div>
                                        <div class="p-6 flex flex-col gap-2">
                                            <h3 class="font-bold text-lg text-gray-900">Mecanización Agrícola y Pecuaria</h3>
                                            <p class="text-sm text-gray-500">Duración: 4 horas</p>
                                        </div>
                                    </div>
                                    <div class="group flex flex-col rounded-xl overflow-hidden bg-white border border-primary-green/20 hover:shadow-lg hover:border-primary-green transition-shadow">
                                        <div class="w-full h-48 bg-gradient-to-br from-primary-green/60 to-primary-green/90"></div>
                                        <div class="p-6 flex flex-col gap-2">
                                            <h3 class="font-bold text-lg text-gray-900">Práctica de Campo - Operación de Maquinaria</h3>
                                            <p class="text-sm text-gray-500">Duración: 3 horas</p>
                                        </div>
                                    </div>
                                    <div class="group flex flex-col rounded-xl overflow-hidden bg-white border border-primary-green/20 hover:shadow-lg hover:border-primary-green transition-shadow">
                                        <div class="w-full h-48 bg-gradient-to-br from-primary-green/70 to-primary-green"></div>
                                        <div class="p-6 flex flex-col gap-2">
                                            <h3 class="font-bold text-lg text-gray-900">Sistemas de Riego y Drenajes</h3>
                                            <p class="text-sm text-gray-500">Duración: 4.5 horas</p>
                                        </div>
                                    </div>
                                    <div class="group flex flex-col rounded-xl overflow-hidden bg-white border border-primary-green/20 hover:shadow-lg hover:border-primary-green transition-shadow">
                                        <div class="w-full h-48 bg-gradient-to-br from-primary-green/80 to-primary-green"></div>
                                        <div class="p-6 flex flex-col gap-2">
                                            <h3 class="font-bold text-lg text-gray-900">Recorrido por Unidades Pecuarias</h3>
                                            <p class="text-sm text-gray-500">Duración: 3 horas</p>
                                        </div>
                                    </div>
                                    <div class="group flex flex-col rounded-xl overflow-hidden bg-white border border-primary-green/20 hover:shadow-lg hover:border-primary-green transition-shadow">
                                        <div class="w-full h-48 bg-gradient-to-br from-primary-green/60 to-primary-green/90"></div>
                                        <div class="p-6 flex flex-col gap-2">
                                            <h3 class="font-bold text-lg text-gray-900">Manejo de Calidad en Cacao</h3>
                                            <p class="text-sm text-gray-500">Duración: 2 horas</p>
                                        </div>
                                    </div>
                                    <div class="group flex flex-col rounded-xl overflow-hidden bg-white border border-primary-green/20 hover:shadow-lg hover:border-primary-green transition-shadow">
                                        <div class="w-full h-48 bg-gradient-to-br from-primary-green/70 to-primary-green"></div>
                                        <div class="p-6 flex flex-col gap-2">
                                            <h3 class="font-bold text-lg text-gray-900">Tour General de la Institución</h3>
                                            <p class="text-sm text-gray-500">Duración: 2 horas</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- CTA Section -->
                        <section class="py-16 md:py-24">
                            <div class="bg-primary-green rounded-xl p-8 md:p-12 lg:p-16 text-center flex flex-col items-center gap-6 shadow-xl">
                                <h2 class="text-3xl md:text-4xl font-bold text-white max-w-2xl">¿Listo para Simplificar tus Visitas Educativas?</h2>
                                <p class="text-white/90 max-w-xl">Únete a cientos de otras instituciones que están optimizando su proceso de programación de visitas con nuestra plataforma.</p>
                                @auth
                                    <a href="{{ route('dashboard') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-white text-primary-green text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary-green/10 transition-colors shadow-lg">
                                        <span class="truncate">Ir al Dashboard</span>
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-white text-primary-green text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary-green/10 transition-colors shadow-lg">
                                        <span class="truncate">Comenzar Ahora</span>
                                    </a>
                                @endauth
                            </div>
                        </section>
                    </main>

                    <!-- Footer -->
                    <footer class="py-12 border-t border-primary-green/20 bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="flex flex-col gap-4">
                                <div class="flex items-center gap-4 text-black">
                                    <div class="size-6 text-primary-green">
                                        <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 2L1 9l4 2.18v6.32L12 22l7-4.5V11.18L23 9 12 2zm0 2.45L19.5 9l-2.75 1.5-4.75-2.6L12 4.45zm-5 4.05L12 11.18v6.32L7 14.5V8.5z"></path>
                                        </svg>
                                    </div>
                                    <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">Sistema de Visitas</h2>
                                </div>
                                <p class="text-sm text-gray-500">Simplificando experiencias educativas mediante tecnología.</p>
                            </div>
                            <div class="md:col-span-2 grid grid-cols-2 sm:grid-cols-3 gap-8">
                                <div class="flex flex-col gap-4">
                                    <h4 class="font-bold text-gray-900">Producto</h4>
                                    <a href="#" class="text-sm text-gray-500 hover:text-primary-green transition-colors">Características</a>
                                    <a href="#" class="text-sm text-gray-500 hover:text-primary-green transition-colors">FAQ</a>
                                </div>
                                <div class="flex flex-col gap-4">
                                    <h4 class="font-bold text-gray-900">Institución</h4>
                                    <a href="#" class="text-sm text-gray-500 hover:text-primary-green transition-colors">Acerca de</a>
                                    <a href="#" class="text-sm text-gray-500 hover:text-primary-green transition-colors">Contacto</a>
                                </div>
                                <div class="flex flex-col gap-4">
                                    <h4 class="font-bold text-gray-900">Legal</h4>
                                    <a href="#" class="text-sm text-gray-500 hover:text-primary-green transition-colors">Términos de Servicio</a>
                                    <a href="#" class="text-sm text-gray-500 hover:text-primary-green transition-colors">Política de Privacidad</a>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 pt-8 border-t border-primary-green/20 text-center text-sm text-gray-500">
                            © {{ date('Y') }} Sistema de Agendamiento de Visitas. Todos los derechos reservados.
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
