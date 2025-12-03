<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'create_visits',
                'display_name' => 'Crear Visitas',
                'description' => 'Puede crear solicitudes de visita'
            ],
            [
                'name' => 'view_own_visits',
                'display_name' => 'Ver Propias Visitas',
                'description' => 'Puede ver sus propias solicitudes de visita'
            ],
            [
                'name' => 'view_all_visits',
                'display_name' => 'Ver Todas las Visitas',
                'description' => 'Puede ver todas las solicitudes de visita'
            ],
            [
                'name' => 'approve_visits',
                'display_name' => 'Aprobar Visitas',
                'description' => 'Puede aprobar solicitudes de visita'
            ],
            [
                'name' => 'reject_visits',
                'display_name' => 'Rechazar Visitas',
                'description' => 'Puede rechazar solicitudes de visita'
            ],
            [
                'name' => 'modify_visits',
                'display_name' => 'Modificar Visitas',
                'description' => 'Puede modificar solicitudes de visita'
            ],
            [
                'name' => 'assign_visits',
                'display_name' => 'Asignar Visitas',
                'description' => 'Puede asignar visitas a otros administradores'
            ],
            [
                'name' => 'manage_activities',
                'display_name' => 'Gestionar Actividades',
                'description' => 'Puede gestionar las actividades disponibles'
            ],
            [
                'name' => 'manage_schedules',
                'display_name' => 'Gestionar Horarios',
                'description' => 'Puede gestionar los horarios disponibles'
            ],
            [
                'name' => 'manage_users',
                'display_name' => 'Gestionar Usuarios',
                'description' => 'Puede gestionar usuarios del sistema'
            ],
            [
                'name' => 'grant_permissions',
                'display_name' => 'Otorgar Permisos',
                'description' => 'Puede otorgar permisos especiales a otros usuarios'
            ],
            [
                'name' => 'view_logs',
                'display_name' => 'Ver Logs',
                'description' => 'Puede ver los logs del sistema'
            ]
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert($permission);
        }
    }
}
