<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitAttendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'name',
        'email',
        'phone',
        'position',
        'identification_number',
        'special_requirements',
        'is_contact_person',
    ];

    protected $casts = [
        'is_contact_person' => 'boolean',
    ];

    /**
     * Get the visit that this attendee belongs to.
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Scope a query to only include contact persons.
     */
    public function scopeContactPersons($query)
    {
        return $query->where('is_contact_person', true);
    }
}
