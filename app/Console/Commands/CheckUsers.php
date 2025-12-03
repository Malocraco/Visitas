<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;

class CheckUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:grant-superadmin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otorgar rol de Super Administrador a un usuario específico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Buscar el usuario
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ No se encontró un usuario con el email: {$email}");
            return 1;
        }
        
        // Buscar el rol de SuperAdmin
        $superAdminRole = Role::where('name', 'superadmin')->first();
        
        if (!$superAdminRole) {
            $this->error("❌ No se encontró el rol de Super Administrador en la base de datos");
            return 1;
        }
        
        // Verificar si ya tiene el rol
        if ($user->hasRole('superadmin')) {
            $this->warn("⚠️  El usuario {$user->name} ya tiene el rol de Super Administrador");
            return 0;
        }
        
        // Confirmar la acción
        if (!$this->confirm("¿Estás seguro de que quieres otorgar el rol de Super Administrador a {$user->name} ({$user->email})?")) {
            $this->info("❌ Operación cancelada");
            return 0;
        }
        
        // Otorgar el rol
        $user->roles()->attach($superAdminRole->id);
        
        $this->info("✅ Rol de Super Administrador otorgado exitosamente a {$user->name}");
        $this->info("📧 Email: {$user->email}");
        $this->info("🆔 ID: {$user->id}");
        
        return 0;
    }
}
