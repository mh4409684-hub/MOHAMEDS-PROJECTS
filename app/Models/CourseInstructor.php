<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseInstructor extends Model
{
    use HasFactory;

    protected $table = 'course_instructors';

    protected $fillable = [
        'course_id',
        'section_id',
        'instructor_staff_id',
        'semester_id',
        'role',
        'assignment_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'end_date' => 'date',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'instructor_staff_id');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }
}
