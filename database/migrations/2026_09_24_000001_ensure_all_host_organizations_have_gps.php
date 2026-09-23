<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\HostOrganization;

return new class extends Migration
{
    /**
     * Run the migrations to backfill accurate GPS coordinates for all host organizations
     */
    public function up(): void
    {
        $orgs = HostOrganization::all();
        foreach ($orgs as $org) {
            $dirty = false;
            if (empty($org->latitude) || empty($org->longitude)) {
                $coords = HostOrganization::getCityDefaultCoordinates($org->city);
                $org->latitude = $coords['lat'];
                $org->longitude = $coords['lon'];
                $dirty = true;
            }
            if (empty($org->geofence_radius_meters) || $org->geofence_radius_meters > 300) {
                $org->geofence_radius_meters = 200; // Strict 200m
                $dirty = true;
            }
            if ($dirty) {
                $org->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
