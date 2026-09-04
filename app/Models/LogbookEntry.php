<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LogbookEntry extends Model
{
    use HasFactory;

    protected $table = 'logbook_entries';

    protected $fillable = [
        'field_placement_id',
        'activity_date',
        'activity_description',
        'skills_learned',
        'challenges',
        'solutions',
        'hours_worked',
        'status',
        'supervisor_comments',
        'supervisor_rejection_reason',
        'submitted_at',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function fieldPlacement(): BelongsTo
    {
        return $this->belongsTo(FieldPlacement::class);
    }

    public function supervisorReview(): HasMany
    {
        return $this->hasMany(SupervisorReview::class, 'logbook_entry_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'related_logbook_id');
    }
}
