<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HostOrganization extends Model
{
    use HasFactory;

    protected $table = 'host_organizations';

    protected $fillable = [
        'name',
        'industry',
        'address',
        'city',
        'phone',
        'email',
        'contact_person',
        'contact_title',
        'description',
        'latitude',
        'longitude',
        'geofence_radius_meters',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'geofence_radius_meters' => 'integer',
        'is_active' => 'boolean',
    ];

    public function fieldPlacements(): HasMany
    {
        return $this->hasMany(FieldPlacement::class);
    }
}
