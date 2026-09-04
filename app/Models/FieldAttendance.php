<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldAttendance extends Model
{
    use HasFactory;

    protected $table = 'field_attendance';

    protected $fillable = [
        'field_placement_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'status',
        'latitude',
        'longitude',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function fieldPlacement(): BelongsTo
    {
        return $this->belongsTo(FieldPlacement::class);
    }
}
