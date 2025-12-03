<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'visitante',
                'display_name' => 'Visitante',
                'description' => 'Usuarios que pueden solicitar visitas a la institución'
            ],
            [
                'name' => 'administrador',
                'display_name' => 'Administrador',
                'description' => 'Administradores que pueden gestionar visitas aprobadas'
            ],
            [
                'name' => 'superadmin',
                'display_name' => 'Super Administrador',
                'description' => 'Super administrador con todos los permisos del sistema'
            ]
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert($role);
        }
    }
}
