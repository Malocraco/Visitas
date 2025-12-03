<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;

class ListSuperAdminsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:list-superadmins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listar todos los Super Administradores del sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('👑 Super Administradores del Sistema');
        $this->info('=====================================');
        
        // Buscar el rol de SuperAdmin
        $superAdminRole = Role::where('name', 'superadmin')->first();
        
        if (!$superAdminRole) {
            $this->error("❌ No se encontró el rol de Super Administrador en la base de datos");
            return 1;
        }
        
        // Obtener todos los usuarios con rol de SuperAdmin
        $superAdmins = User::whereHas('roles', function($query) {
            $query->where('name', 'superadmin');
        })->get();
        
        if ($superAdmins->isEmpty()) {
            $this->warn("⚠️  No hay Super Administradores en el sistema");
            return 0;
        }
        
        $this->info("📊 Total de Super Administradores: {$superAdmins->count()}");
        $this->info("");
        
        foreach ($superAdmins as $index => $user) {
            $this->info("👤 " . ($index + 1) . ". {$user->name}");
            $this->line("   📧 Email: {$user->email}");
            $this->line("   🆔 ID: {$user->id}");
            $this->line("   📅 Registrado: {$user->created_at->format('d/m/Y H:i')}");
            $this->line("   🔄 Última actualización: {$user->updated_at->format('d/m/Y H:i')}");
            
            if ($user->id === auth()->id()) {
                $this->line("   ⭐ Usuario actual");
            }
            
            $this->info("");
        }
        
        return 0;
    }
}
