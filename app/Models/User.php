<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'institution_name',
        'institution_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the roles that belong to this user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Get the permissions that belong to this user.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
                    ->withPivot(['granted_by', 'granted_at', 'expires_at'])
                    ->withTimestamps();
    }

    /**
     * Get the visits requested by this user.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Get the visits assigned to this admin.
     */
    public function assignedVisits(): HasMany
    {
        return $this->hasMany(Visit::class, 'assigned_admin_id');
    }

    /**
     * Get the visits approved by this admin.
     */
    public function approvedVisits(): HasMany
    {
        return $this->hasMany(Visit::class, 'approved_by');
    }

    /**
     * Get the messages sent by this user.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(VisitMessage::class);
    }

    /**
     * Get the logs created by this user.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(VisitLog::class);
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole($role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if the user has any of the specified roles.
     */
    public function hasAnyRole($roles): bool
    {
        return $this->roles()->whereIn('name', (array) $roles)->exists();
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission($permission): bool
    {
        // Check direct permissions
        $hasDirectPermission = $this->permissions()
            ->where('name', $permission)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->exists();

        if ($hasDirectPermission) {
            return true;
        }

        // Check role-based permissions
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }

    /**
     * Check if the user has any of the specified permissions.
     */
    public function hasAnyPermission($permissions): bool
    {
        foreach ((array) $permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('administrador') || $this->isSuperAdmin();
    }

    /**
     * Check if the user is a visitor.
     */
    public function isVisitor(): bool
    {
        return $this->hasRole('visitante');
    }

    /**
     * Get the primary role of the user.
     */
    public function getPrimaryRole()
    {
        return $this->roles()->first();
    }
}
