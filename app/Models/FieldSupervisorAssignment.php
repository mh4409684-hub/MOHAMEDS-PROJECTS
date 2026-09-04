<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldSupervisorAssignment extends Model
{
    use HasFactory;

    protected $table = 'field_supervisor_assignments';

    protected $fillable = [
        'field_placement_id',
        'supervisor_staff_id',
        'assigned_date',
        'unassigned_date',
        'status',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'unassigned_date' => 'date',
    ];

    public function fieldPlacement(): BelongsTo
    {
        return $this->belongsTo(FieldPlacement::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'supervisor_staff_id');
    }
}
