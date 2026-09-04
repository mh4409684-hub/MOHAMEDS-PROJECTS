<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldPlacement extends Model
{
    use HasFactory;

    protected $table = 'field_placements';

    protected $fillable = [
        'student_id',
        'host_organization_id',
        'academic_year_id',
        'start_date',
        'end_date',
        'status',
        'total_days',
        'days_completed',
        'field_progress',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'field_progress' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function hostOrganization(): BelongsTo
    {
        return $this->belongsTo(HostOrganization::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function supervisorAssignments(): HasMany
    {
        return $this->hasMany(FieldSupervisorAssignment::class);
    }

    public function logbookEntries(): HasMany
    {
        return $this->hasMany(LogbookEntry::class);
    }

    public function weeklyReports(): HasMany
    {
        return $this->hasMany(WeeklyReport::class);
    }

    public function fieldAttendances(): HasMany
    {
        return $this->hasMany(FieldAttendance::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'related_field_placement_id');
    }
}
