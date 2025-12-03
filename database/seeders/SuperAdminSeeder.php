<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario SuperAdmin
        $superAdmin = User::create([
            'name' => 'Super Administrador',
            'email' => 'superadmin@institucion.com',
            'password' => Hash::make('password123'),
        ]);

        // Asignar rol de superadmin
        $superAdminRole = DB::table('roles')->where('name', 'superadmin')->first();
        if ($superAdminRole) {
            DB::table('user_roles')->insert([
                'user_id' => $superAdmin->id,
                'role_id' => $superAdminRole->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Asignar todos los permisos al superadmin
        $permissions = DB::table('permissions')->get();
        foreach ($permissions as $permission) {
            DB::table('user_permissions')->insert([
                'user_id' => $superAdmin->id,
                'permission_id' => $permission->id,
                'granted_by' => $superAdmin->id,
                'granted_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('SuperAdmin creado exitosamente:');
        $this->command->info('Email: superadmin@institucion.com');
        $this->command->info('Password: password123');
    }
}
