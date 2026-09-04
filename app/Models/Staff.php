<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campus_id',
        'department_id',
        'staff_type',
        'designation',
        'employment_date',
        'employment_status',
        'bio',
        'office_location',
    ];

    protected $casts = [
        'employment_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function fieldSupervisorAssignments(): HasMany
    {
        return $this->hasMany(FieldSupervisorAssignment::class, 'supervisor_staff_id');
    }

    public function supervisorReviews(): HasMany
    {
        return $this->hasMany(SupervisorReview::class, 'supervisor_staff_id');
    }

    public function classSessions(): HasMany
    {
        return $this->hasMany(ClassSession::class, 'lecturer_staff_id');
    }

    public function courseInstructors(): HasMany
    {
        return $this->hasMany(CourseInstructor::class, 'instructor_staff_id');
    }
}
