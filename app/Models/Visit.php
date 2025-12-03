<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'institution_name',
        'contact_person',
        'contact_email',
        'contact_phone',
        'contact_position',
        'institution_description',
        'institution_type',
        'expected_participants',
        'preferred_date',
        'preferred_start_time',
        'preferred_end_time',
        'visit_purpose',
        'special_requirements',
        'other_activities',
        'status',
        'assigned_admin_id',
        'approved_by',
        'approved_at',
        'confirmed_date',
        'confirmed_start_time',
        'confirmed_end_time',
        'admin_notes',
        'rejection_reason',
        'restaurant_service',
        'restaurant_participants',
        'restaurant_notes',
        'postponed_at',
        'postponed_by',
        'postponement_reason',
        'suggested_date',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'preferred_start_time' => 'datetime:H:i',
        'preferred_end_time' => 'datetime:H:i',
        'confirmed_date' => 'date',
        'confirmed_start_time' => 'datetime:H:i',
        'confirmed_end_time' => 'datetime:H:i',
        'approved_at' => 'datetime',
        'postponed_at' => 'datetime',
        'suggested_date' => 'date',
        'restaurant_service' => 'boolean',
        'restaurant_participants' => 'integer',
        'expected_participants' => 'integer',
    ];

    /**
     * Get the user that requested this visit.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin assigned to this visit.
     */
    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }

    /**
     * Get the admin that approved this visit.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the admin that postponed this visit.
     */
    public function postponedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'postponed_by');
    }

    /**
     * Get the activities for this visit.
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(VisitActivity::class, 'visit_activities_visits')
                    ->withPivot(['participants', 'notes'])
                    ->withTimestamps();
    }

    /**
     * Get the messages for this visit.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(VisitMessage::class);
    }

    /**
     * Get the attendees for this visit.
     */
    public function attendees(): HasMany
    {
        return $this->hasMany(VisitAttendee::class);
    }

    /**
     * Get the logs for this visit.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(VisitLog::class);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('preferred_date', [$startDate, $endDate]);
    }
}
