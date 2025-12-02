<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario Administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@institucion.com',
            'password' => Hash::make('password123'),
            'phone' => '1234567890',
            'institution_name' => 'Institución',
            'institution_type' => 'otro',
        ]);

        // Asignar rol de administrador
        $adminRole = DB::table('roles')->where('name', 'administrador')->first();
        if ($adminRole) {
            DB::table('user_roles')->insert([
                'user_id' => $admin->id,
                'role_id' => $adminRole->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Administrador creado exitosamente:');
        $this->command->info('Email: admin@institucion.com');
        $this->command->info('Password: password123');
    }
}

