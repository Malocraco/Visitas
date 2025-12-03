<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class SettingsHelper
{
    /**
     * Obtener una configuración específica
     */
    public static function get($key, $default = null)
    {
        $settings = self::getAllSettings();
        
        $keys = explode('.', $key);
        $value = $settings;
        
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }
        
        return $value;
    }
    
    /**
     * Obtener todas las configuraciones
     */
    public static function getAllSettings()
    {
        return Cache::remember('system_settings', 3600, function () {
            return [
                'general' => [
                    'site_name' => config('app.name', 'Sistema de Visitas'),
                    'site_description' => 'Sistema de gestión de visitas para instituciones educativas',
                    'contact_email' => 'admin@institucion.edu',
                    'contact_phone' => '+1 234 567 8900',
                    'address' => 'Dirección de la institución',
                    'business_hours' => 'Lunes a Viernes: 8:00 AM - 5:00 PM',
                    'max_visitors_per_day' => 50,
                    'advance_booking_days' => 30,
                ],
                'email' => [
                    'mail_from_name' => config('mail.from.name', 'Sistema de Visitas'),
                    'mail_from_address' => config('mail.from.address', 'noreply@institucion.edu'),
                    'mail_host' => config('mail.mailers.smtp.host', 'smtp.mailtrap.io'),
                    'mail_port' => config('mail.mailers.smtp.port', 2525),
                    'mail_username' => config('mail.mailers.smtp.username', ''),
                    'mail_password' => config('mail.mailers.smtp.password', ''),
                    'mail_encryption' => config('mail.mailers.smtp.encryption', 'tls'),
                    'notify_admin_on_visit_request' => true,
                    'notify_visitor_on_approval' => true,
                    'notify_visitor_on_rejection' => true,
                ],
                'security' => [
                    'session_timeout' => 120,
                    'max_login_attempts' => 5,
                    'lockout_duration' => 15,
                    'require_password_change' => false,
                    'password_expiry_days' => 90,
                    'enable_two_factor' => false,
                    'enable_audit_log' => true,
                    'backup_frequency' => 'daily',
                    'retain_backups_days' => 30,
                ],
            ];
        });
    }
    
    /**
     * Guardar configuraciones
     */
    public static function saveSettings($settings)
    {
        Cache::put('system_settings', $settings, 3600);
        
        // También guardar en archivo de configuración
        $configPath = config_path('custom.php');
        $configContent = "<?php\n\nreturn " . var_export($settings, true) . ";\n";
        
        if (!file_exists($configPath)) {
            file_put_contents($configPath, $configContent);
        }
    }
    
    /**
     * Limpiar caché de configuraciones
     */
    public static function clearCache()
    {
        Cache::forget('system_settings');
    }
    
    /**
     * Obtener configuración general
     */
    public static function getGeneral($key = null, $default = null)
    {
        if ($key) {
            return self::get("general.{$key}", $default);
        }
        return self::get('general', []);
    }
    
    /**
     * Obtener configuración de email
     */
    public static function getEmail($key = null, $default = null)
    {
        if ($key) {
            return self::get("email.{$key}", $default);
        }
        return self::get('email', []);
    }
    
    /**
     * Obtener configuración de seguridad
     */
    public static function getSecurity($key = null, $default = null)
    {
        if ($key) {
            return self::get("security.{$key}", $default);
        }
        return self::get('security', []);
    }
}
