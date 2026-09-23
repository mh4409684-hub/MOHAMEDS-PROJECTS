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

    /**
     * Ensure every organization always has valid GPS coordinates and geofence radius
     */
    protected static function booted()
    {
        static::saving(function ($org) {
            if (empty($org->latitude) || empty($org->longitude)) {
                $coords = self::getCityDefaultCoordinates($org->city);
                $org->latitude = $coords['lat'];
                $org->longitude = $coords['lon'];
            }
            if (empty($org->geofence_radius_meters)) {
                $org->geofence_radius_meters = 200; // Strict 200 meters
            }
        });
    }

    /**
     * Accurate default coordinates for major Tanzanian cities and CBE campuses
     */
    public static function getCityDefaultCoordinates(?string $city): array
    {
        $city = mb_strtolower(trim($city ?? ''));
        if (str_contains($city, 'dodo')) {
            return ['lat' => -6.182440, 'lon' => 35.748370]; // Dodoma CBE / City Centre
        }
        if (str_contains($city, 'arush')) {
            return ['lat' => -3.372300, 'lon' => 36.694400]; // Arusha CBD
        }
        if (str_contains($city, 'mwanz')) {
            return ['lat' => -2.516400, 'lon' => 32.903300]; // Mwanza CBD
        }
        if (str_contains($city, 'mbey')) {
            return ['lat' => -8.909400, 'lon' => 33.460800]; // Mbeya CBD
        }
        if (str_contains($city, 'tanga')) {
            return ['lat' => -5.068900, 'lon' => 39.098800]; // Tanga CBD
        }
        if (str_contains($city, 'moro')) {
            return ['lat' => -6.827800, 'lon' => 37.659100]; // Morogoro CBD
        }
        if (str_contains($city, 'zanz')) {
            return ['lat' => -6.163900, 'lon' => 39.197900]; // Zanzibar Stone Town
        }
        if (str_contains($city, 'mosh') || str_contains($city, 'kili')) {
            return ['lat' => -3.339600, 'lon' => 37.340300]; // Moshi CBD
        }
        // Default: Dar es Salaam (CBE Dar Campus / Samora / Posta)
        return ['lat' => -6.816064, 'lon' => 39.280358];
    }

    public function fieldPlacements(): HasMany
    {
        return $this->hasMany(FieldPlacement::class);
    }
}
