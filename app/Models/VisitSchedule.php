<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_of_week',
        'start_time',
        'end_time',
        'max_visits_per_slot',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_visits_per_slot' => 'integer',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Scope a query to only include active schedules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by day of week.
     */
    public function scopeByDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }
}
