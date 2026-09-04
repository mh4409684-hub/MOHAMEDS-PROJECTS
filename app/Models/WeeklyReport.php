<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyReport extends Model
{
    use HasFactory;

    protected $table = 'weekly_reports';

    protected $fillable = [
        'field_placement_id',
        'week_number',
        'week_start_date',
        'week_end_date',
        'activities_completed',
        'skills_acquired',
        'challenges',
        'solutions',
        'summary',
        'status',
        'supervisor_comments',
        'submitted_at',
        'approved_at',
    ];

    protected $casts = [
        'week_start_date' => 'date',
        'week_end_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function fieldPlacement(): BelongsTo
    {
        return $this->belongsTo(FieldPlacement::class);
    }

    public function supervisorReview(): HasMany
    {
        return $this->hasMany(SupervisorReview::class, 'weekly_report_id');
    }
}
