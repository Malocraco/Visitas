<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();
        
        // Asegurar que los roles estén cargados
        if (!$user->relationLoaded('roles')) {
            $user->load('roles');
        }

        // Los superadmins tienen acceso a todas las rutas
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Normalizar los roles (pueden venir como string separado por comas o como array)
        $requiredRoles = [];
        foreach ($roles as $role) {
            // Si el rol contiene comas, separarlo
            if (strpos($role, ',') !== false) {
                $requiredRoles = array_merge($requiredRoles, explode(',', $role));
            } else {
                $requiredRoles[] = trim($role);
            }
        }
        
        // Eliminar duplicados y espacios
        $requiredRoles = array_unique(array_map('trim', $requiredRoles));

        // Verificar si el usuario tiene alguno de los roles requeridos
        if (!$user->hasAnyRole($requiredRoles)) {
            $userRoles = $user->roles->pluck('name')->toArray();
            $rolesString = implode(', ', $userRoles ?: ['ninguno']);
            $requiredString = implode(', ', $requiredRoles);
            abort(403, "No tienes permisos para acceder a esta sección. Requieres uno de estos roles: {$requiredString}. Tu rol actual: {$rolesString}.");
        }

        return $next($request);
    }
}
