<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'sla_breached' => 'boolean',
        'first_response_due_at' => 'datetime',
        'resolution_due_at' => 'datetime',
        'first_responded_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Relationships (as defined in the Trainee 3 guide)

    // User aliyeanzisha Ticket (Requester)
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    // Agent aliyetengewa kushughulikia
    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    // Category ya Ticket (Owned by T2)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Department (Owned by T1)
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // SLA Policy (Owned by T2)
    public function slaPolicy()
    {
        return $this->belongsTo(SlaPolicy::class);
    }
}