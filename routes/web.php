<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VisitRequestController;
use App\Http\Controllers\VisitManagementController;

// Ruta principal
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Dashboard específico por rol
    Route::get('/dashboard/visitor', [DashboardController::class, 'visitor'])
        ->name('dashboard.visitor')
        ->middleware('role:visitante');
    
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->name('dashboard.admin')
        ->middleware('role:administrador,superadmin');
    
    Route::get('/dashboard/superadmin', [DashboardController::class, 'superadmin'])
        ->name('dashboard.superadmin')
        ->middleware('role:superadmin');
    
    // Rutas para solicitudes de visita (solo visitantes)
    Route::middleware('role:visitante')->group(function () {
        Route::get('/visits/request', [VisitRequestController::class, 'showForm'])
            ->name('visits.request');
        Route::post('/visits/submit', [VisitRequestController::class, 'submitRequest'])
            ->name('visits.submit');
        Route::get('/visits/my-requests', [VisitRequestController::class, 'myRequests'])
            ->name('visits.my-requests');
        Route::get('/visits/{id}', [VisitRequestController::class, 'showRequest'])
            ->name('visits.show');
        Route::get('/visits/available-dates', [VisitRequestController::class, 'getAvailableDates'])
            ->name('visits.available-dates');
    });
    
    // Rutas para gestión de visitas (SuperAdmin y Administrador)
    Route::middleware('role:superadmin,administrador')->group(function () {
        // Reportes (compartido)
        Route::get('/admin/visits/reports', [VisitManagementController::class, 'reports'])
            ->name('admin.visits.reports');
        
        // Calendario (compartido)
        Route::get('/admin/visits/calendar', [VisitManagementController::class, 'calendar'])
            ->name('admin.visits.calendar');
        
        // Detalles de visita (compartido)
        Route::get('/admin/visits/{id}/details', [VisitManagementController::class, 'visitDetails'])
            ->name('admin.visits.details');
        
        // Completar visita (compartido)
        Route::post('/admin/visits/{id}/complete', [VisitManagementController::class, 'completeVisit'])
            ->name('admin.visits.complete');
    });
    
    // Rutas específicas del SuperAdmin
    Route::middleware('role:superadmin')->group(function () {
        // Solicitudes Pendientes
        Route::get('/admin/visits/pending', [VisitManagementController::class, 'pendingRequests'])
            ->name('admin.visits.pending');
        
        // Todas las Visitas
        Route::get('/admin/visits/all', [VisitManagementController::class, 'allVisits'])
            ->name('admin.visits.all');
        
        // Acciones sobre visitas
        Route::post('/admin/visits/{id}/approve', [VisitManagementController::class, 'approveVisit'])
            ->name('admin.visits.approve');
        Route::post('/admin/visits/{id}/postpone', [VisitManagementController::class, 'postponeVisit'])
            ->name('admin.visits.postpone');
    });
    
    // Rutas específicas del Administrador y SuperAdmin
    Route::middleware('role:administrador,superadmin')->group(function () {
        // Visitas Aprobadas
        Route::get('/admin/visits/approved', [VisitManagementController::class, 'approvedVisits'])
            ->name('admin.visits.approved');
    });
    
    // Gestión de Usuarios (solo SuperAdmin)
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/admin/users', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])
            ->name('admin.users.index');
        Route::get('/admin/users/create', [App\Http\Controllers\Admin\UserManagementController::class, 'create'])
            ->name('admin.users.create');
        Route::post('/admin/users', [App\Http\Controllers\Admin\UserManagementController::class, 'store'])
            ->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])
            ->name('admin.users.edit');
        Route::put('/admin/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])
            ->name('admin.users.update');
        Route::delete('/admin/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])
            ->name('admin.users.destroy');
        
        // Roles y Permisos
        Route::get('/admin/roles', [App\Http\Controllers\Admin\RolePermissionController::class, 'index'])
            ->name('admin.roles.index');
        Route::get('/admin/roles/{role}/edit', [App\Http\Controllers\Admin\RolePermissionController::class, 'edit'])
            ->name('admin.roles.edit');
        Route::put('/admin/roles/{role}', [App\Http\Controllers\Admin\RolePermissionController::class, 'update'])
            ->name('admin.roles.update');
        Route::delete('/admin/roles/{role}', [App\Http\Controllers\Admin\RolePermissionController::class, 'destroy'])
            ->name('admin.roles.destroy');
        
    });
    
    // Configuración de Cuenta (Todos los usuarios autenticados)
    Route::get('/admin/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])
        ->name('admin.settings.index');
    Route::put('/admin/settings/profile', [App\Http\Controllers\Admin\SettingsController::class, 'updateProfile'])
        ->name('admin.settings.profile');
    Route::put('/admin/settings/password', [App\Http\Controllers\Admin\SettingsController::class, 'changePassword'])
        ->name('admin.settings.password');
 });

// Rutas del Sistema de Chat
Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/create', [App\Http\Controllers\ChatController::class, 'create'])->name('chat.create');
    Route::post('/chat', [App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/{roomId}', [App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{roomId}/message', [App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send-message');
    Route::get('/chat/{roomId}/messages', [App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.get-messages');
    Route::put('/chat/{roomId}/message/{messageId}', [App\Http\Controllers\ChatController::class, 'editMessage'])->name('chat.edit-message');
    Route::delete('/chat/{roomId}/message/{messageId}', [App\Http\Controllers\ChatController::class, 'deleteMessage'])->name('chat.delete-message');
    Route::post('/chat/{roomId}/close', [App\Http\Controllers\ChatController::class, 'close'])->name('chat.close');
    Route::post('/chat/{roomId}/resolve', [App\Http\Controllers\ChatController::class, 'resolve'])->name('chat.resolve');
    Route::delete('/chat/{roomId}', [App\Http\Controllers\ChatController::class, 'destroy'])->name('chat.destroy');
    Route::post('/chat/online-status', [App\Http\Controllers\ChatController::class, 'updateOnlineStatus'])->name('chat.online-status');
    
    // Estadísticas de chat (solo para administradores)
    Route::get('/chat/statistics', [App\Http\Controllers\ChatController::class, 'statistics'])->name('chat.statistics');
    
    // Notificaciones de chat en tiempo real
    Route::get('/chat/notifications', [App\Http\Controllers\ChatController::class, 'getNotifications'])->name('chat.notifications');
});
