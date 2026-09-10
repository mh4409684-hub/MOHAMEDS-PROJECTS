<?php

namespace App\Services;

use App\Models\FieldPlacement;
use App\Models\FieldSupervisorAssignment;
use App\Models\FieldAttendance;
use App\Models\Notification;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class FieldManagementService
{
    /**
     * Create field placement for student
     */
    public function createFieldPlacement(array $data): FieldPlacement
    {
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $totalDays = $startDate->diffInDays($endDate);

        $placement = FieldPlacement::create([
            'student_id' => $data['student_id'],
            'host_organization_id' => $data['host_organization_id'],
            'academic_year_id' => $data['academic_year_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
            'total_days' => $totalDays,
            'days_completed' => 0,
            'field_progress' => 0,
            'notes' => $data['notes'] ?? null,
        ]);

        // Notify student
        $student = $placement->student;
        Notification::create([
            'user_id' => $student->user_id,
            'type' => 'field_placement_created',
            'title' => 'Field Placement Created',
            'message' => 'You have been assigned to ' . $placement->hostOrganization->name . ' for field placement.',
            'related_field_placement_id' => $placement->id,
            'related_student_id' => $student->id,
        ]);

        return $placement->load('student.user', 'hostOrganization', 'academicYear');
    }

    /**
     * Assign field supervisor to placement
     */
    public function assignSupervisor(FieldPlacement $placement, int $supervisorId): FieldSupervisorAssignment
    {
        // Deactivate existing assignment if any
        $placement->supervisorAssignments()
            ->where('status', 'active')
            ->update(['status' => 'completed', 'unassigned_date' => now()]);

        // Create new assignment
        $assignment = FieldSupervisorAssignment::create([
            'field_placement_id' => $placement->id,
            'supervisor_staff_id' => $supervisorId,
            'assigned_date' => now(),
            'status' => 'active',
        ]);

        // Notify supervisor
        $supervisor = $assignment->supervisor;
        Notification::create([
            'user_id' => $supervisor->user_id,
            'type' => 'supervisor_assigned',
            'title' => 'New Supervision Assignment',
            'message' => 'You have been assigned to supervise ' . $placement->student->user->name . ' at ' . $placement->hostOrganization->name,
            'related_field_placement_id' => $placement->id,
        ]);

        return $assignment->load('fieldPlacement.student.user', 'supervisor.user');
    }

    /**
     * Record field attendance
     */
    public function recordFieldAttendance(FieldPlacement $placement, array $data): FieldAttendance
    {
        $attendance = FieldAttendance::updateOrCreate(
            [
                'field_placement_id' => $placement->id,
                'attendance_date' => $data['attendance_date'],
            ],
            [
                'check_in_time' => $data['check_in_time'] ?? null,
                'check_out_time' => $data['check_out_time'] ?? null,
                'status' => $data['status'],
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]
        );

        // Update field placement progress
        $this->updateFieldProgress($placement);

        return $attendance;
    }

    /**
     * Update field placement progress
     */
    public function updateFieldProgress(FieldPlacement $placement): FieldPlacement
    {
        $attendances = $placement->fieldAttendances()->get();
        $presentDays = $attendances->whereIn('status', ['present', 'late'])->count();
        $daysCompleted = $presentDays;
        
        $progress = $placement->total_days > 0 
            ? round(($daysCompleted / $placement->total_days) * 100, 2)
            : 0;

        $placement->update([
            'days_completed' => $daysCompleted,
            'field_progress' => min($progress, 100),
        ]);

        // If progress reaches 100%, mark as completed
        if ($progress >= 100) {
            $placement->update(['status' => 'completed']);
            
            $student = $placement->student;
            Notification::create([
                'user_id' => $student->user_id,
                'type' => 'field_placement_completed',
                'title' => 'Field Placement Completed',
                'message' => 'Congratulations! You have completed your field placement.',
                'related_field_placement_id' => $placement->id,
                'related_student_id' => $student->id,
            ]);
        }

        return $placement->refresh();
    }

    /**
     * Get field placements by status
     */
    public function getFieldPlacementsByStatus(string $status): Collection
    {
        return FieldPlacement::where('status', $status)
            ->with(['student.user', 'hostOrganization', 'supervisorAssignments.supervisor.user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get active field placements for academic year
     */
    public function getActivePlacementsForAcademicYear(int $academicYearId): Collection
    {
        return FieldPlacement::where('academic_year_id', $academicYearId)
            ->where('status', 'active')
            ->with(['student.user', 'hostOrganization', 'supervisorAssignments.supervisor.user'])
            ->get();
    }

    /**
     * Get student field attendance summary
     */
    public function getFieldAttendanceSummary(FieldPlacement $placement): array
    {
        $attendances = $placement->fieldAttendances()->get();
        $totalDays = $placement->total_days;
        
        return [
            'total_days' => $totalDays,
            'present_days' => $attendances->where('status', 'present')->count(),
            'absent_days' => $attendances->where('status', 'absent')->count(),
            'late_days' => $attendances->where('status', 'late')->count(),
            'excused_days' => $attendances->where('status', 'excused')->count(),
            'attendance_percentage' => $totalDays > 0 
                ? round((($attendances->where('status', 'present')->count() + $attendances->where('status', 'late')->count()) / $totalDays) * 100, 2)
                : 0,
        ];
    }

    /**
     * Get field attendance for date range
     */
    public function getFieldAttendanceByDateRange(FieldPlacement $placement, $startDate, $endDate): Collection
    {
        return $placement->fieldAttendances()
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->orderBy('attendance_date', 'desc')
            ->get();
    }

    /**
     * Verify GPS location for attendance
     */
    public function verifyGPSLocation(FieldPlacement $placement, string $latitude, string $longitude, float $tolerance = 1.0): bool
    {
        // Get organization location (in real implementation, store org coordinates)
        // For now, return true as placeholder
        // In production, calculate distance using Haversine formula or similar
        
        return true;
    }

    /**
     * Suspend field placement
     */
    public function suspendPlacement(FieldPlacement $placement, string $reason): FieldPlacement
    {
        $placement->update([
            'status' => 'suspended',
            'notes' => ($placement->notes ?? '') . "\n\nSuspended: " . $reason,
        ]);

        $student = $placement->student;
        Notification::create([
            'user_id' => $student->user_id,
            'type' => 'field_placement_suspended',
            'title' => 'Field Placement Suspended',
            'message' => 'Your field placement has been suspended. Reason: ' . $reason,
            'related_field_placement_id' => $placement->id,
            'related_student_id' => $student->id,
        ]);

        return $placement->refresh();
    }

    /**
     * Resume field placement
     */
    public function resumePlacement(FieldPlacement $placement): FieldPlacement
    {
        $placement->update(['status' => 'active']);

        $student = $placement->student;
        Notification::create([
            'user_id' => $student->user_id,
            'type' => 'field_placement_resumed',
            'title' => 'Field Placement Resumed',
            'message' => 'Your field placement has been resumed.',
            'related_field_placement_id' => $placement->id,
            'related_student_id' => $student->id,
        ]);

        return $placement->refresh();
    }

    /**
     * Calculate distance in meters between two GPS coordinate points using Haversine formula
     */
    public function calculateDistanceMeters(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // meters

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 1);
    }

    /**
     * Verify if given coordinates are within the organization geofence
     */
    public function verifyGeofence(FieldPlacement $placement, float $studentLat, float $studentLon): array
    {
        $org = $placement->hostOrganization;

        if (!$org || !$org->latitude || !$org->longitude) {
            return [
                'within_geofence' => true,
                'distance' => 0,
                'message' => 'No GPS coordinate baseline configured for host organization.',
            ];
        }

        $distance = $this->calculateDistanceMeters(
            $studentLat,
            $studentLon,
            (float) $org->latitude,
            (float) $org->longitude
        );

        $allowedRadius = $org->geofence_radius_meters ?: 300;
        $isWithin = $distance <= $allowedRadius;

        return [
            'within_geofence' => $isWithin,
            'distance' => $distance,
            'allowed_radius' => $allowedRadius,
            'message' => $isWithin
                ? "Location verified successfully ({$distance}m from host premises)."
                : "You are {$distance}m away from {$org->name}. Max allowed radius is {$allowedRadius}m.",
        ];
    }
}

