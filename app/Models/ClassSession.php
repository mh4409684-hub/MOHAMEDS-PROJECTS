<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassSession extends Model
{
    use HasFactory;

    protected $table = 'class_sessions';

    protected $fillable = [
        'course_id',
        'section_id',
        'lecturer_staff_id',
        'semester_id',
        'session_date',
        'start_time',
        'end_time',
        'room',
        'attendance_code',
        'qr_code',
        'qr_expires_at',
        'status',
        'expected_students',
        'notes',
    ];

    protected $casts = [
        'session_date' => 'date',
        'qr_expires_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'lecturer_staff_id');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(ClassAttendance::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'related_class_session_id');
    }
}
