<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VisitActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'instructor',
        'duration_minutes',
        'max_participants',
        'is_active',
        'requirements',
        'location',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_minutes' => 'integer',
        'max_participants' => 'integer',
    ];

    /**
     * Get the visits that include this activity.
     */
    public function visits(): BelongsToMany
    {
        return $this->belongsToMany(Visit::class, 'visit_activities_visits')
                    ->withPivot(['participants', 'notes'])
                    ->withTimestamps();
    }

    /**
     * Scope a query to only include active activities.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
