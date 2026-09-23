<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LogbookEntry;
use App\Models\WeeklyReport;
use App\Models\FieldPlacement;
use App\Models\ClassAttendance;
use App\Models\Notification;
use App\Services\LogbookService;
use App\Services\FieldManagementService;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    protected LogbookService $logbookService;
    protected FieldManagementService $fieldService;
    protected AttendanceService $attendanceService;

    public function __construct(
        LogbookService $logbookService,
        FieldManagementService $fieldService,
        AttendanceService $attendanceService
    ) {
        $this->logbookService = $logbookService;
        $this->fieldService = $fieldService;
        $this->attendanceService = $attendanceService;
    }

    /**
     * Show student dashboard
     */
    public function index()
    {
        $user = auth()->user();
        $student = $user->student;

        if (!$student) {
            $student = \App\Models\Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'campus_id' => $user->campus_id ?? 1,
                    'programme_id' => 1,
                    'year_of_study' => 1,
                    'enrollment_status' => 'enrolled',
                ]
            );
        }

        $fieldPlacement = $student->fieldPlacements()
            ->where('status', 'active')
            ->first();

        $dashboardData = [
            'student' => $student,
            'field_progress' => 0,
            'days_remaining' => 0,
            'attendance_percentage' => 0,
            'pending_activities' => 0,
            'approved_activities' => 0,
        ];

        if ($fieldPlacement) {
            $logbookStats = $this->logbookService->getLogbookStatistics($fieldPlacement);
            $fieldStats = $this->fieldService->getFieldAttendanceSummary($fieldPlacement);

            $dashboardData['field_progress'] = $fieldPlacement->field_progress;
            $dashboardData['days_remaining'] = $fieldPlacement->total_days - $fieldPlacement->days_completed;
            $dashboardData['attendance_percentage'] = $fieldStats['attendance_percentage'];
            $dashboardData['pending_activities'] = $logbookStats['pending_entries'];
            $dashboardData['approved_activities'] = $logbookStats['approved_entries'];
        }

        $classAttendance = ClassAttendance::whereHas('classSession', function ($q) {
            $q->where('semester_id', session('current_semester_id') ?? 1);
        })
        ->where('student_id', $student->id)
        ->get();

        $totalClasses = ClassAttendance::whereHas('classSession', function ($q) {
            $q->where('semester_id', session('current_semester_id') ?? 1);
        })
        ->where('student_id', $student->id)
        ->count();

        $classAttendancePercentage = $totalClasses > 0
            ? round(($classAttendance->where('status', 'present')->count() / $totalClasses) * 100, 2)
            : 0;

        $notifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->limit(5)
            ->get();

        return view('student.dashboard', compact('dashboardData', 'fieldPlacement', 'classAttendancePercentage', 'notifications'));
    }

    /**
     * Show student profile
     */
    public function profile()
    {
        $user = auth()->user();
        $student = $user->student;
        $student->load('programme', 'section', 'campus', 'academicYear');

        return view('student.profile', compact('user', 'student'));
    }

    /**
     * Update student profile
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $validated['profile_picture'] = $path;
        }

        $user->update($validated);

        return redirect()->route('student.profile')
            ->with('success', 'Profile updated successfully');
    }

    /**
     * Show e-logbook
     */
    public function eLogbook()
    {
        $user = auth()->user();
        $student = $user->student;

        $fieldPlacement = $student->fieldPlacements()
            ->where('status', 'active')
            ->first();

        if (!$fieldPlacement) {
            return redirect()->route('student.dashboard')
                ->with('warning', 'You do not have an active field placement');
        }

        $logbookEntries = $this->logbookService->getLogbookEntries($fieldPlacement);
        $stats = $this->logbookService->getLogbookStatistics($fieldPlacement);

        return view('student.elogbook.index', compact('fieldPlacement', 'logbookEntries', 'stats'));
    }

    /**
     * Create logbook entry
     */
    public function createLogbookEntry()
    {
        $user = auth()->user();
        $student = $user->student;

        $fieldPlacement = $student->fieldPlacements()
            ->where('status', 'active')
            ->first();

        if (!$fieldPlacement) {
            return redirect()->route('student.elogbook')
                ->with('warning', 'No active field placement');
        }

        return view('student.elogbook.create', compact('fieldPlacement'));
    }

    /**
     * Store logbook entry
     */
    public function storeLogbookEntry(Request $request)
    {
        $user = auth()->user();
        $student = $user->student;

        $fieldPlacement = $student->fieldPlacements()
            ->where('status', 'active')
            ->first();

        if (!$fieldPlacement) {
            return redirect()->route('student.elogbook')
                ->with('error', 'No active field placement');
        }

        $validated = $request->validate([
            'activity_date' => 'required|date',
            'activity_description' => 'required|string',
            'skills_learned' => 'nullable|string',
            'challenges' => 'nullable|string',
            'solutions' => 'nullable|string',
            'hours_worked' => 'required|integer|min:1|max:24',
        ]);

        try {
            $entry = $this->logbookService->createLogbookEntry(array_merge(
                $validated,
                ['field_placement_id' => $fieldPlacement->id]
            ));

            return redirect()->route('student.elogbook.show', $entry->id)
                ->with('success', 'Logbook entry created');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show logbook entry
     */
    public function showLogbookEntry(LogbookEntry $entry)
    {
        $user = auth()->user();
        $student = $user->student;

        if ($entry->fieldPlacement->student_id !== $student->id) {
            abort(403);
        }

        return view('student.elogbook.show', compact('entry'));
    }

    /**
     * Edit logbook entry
     */
    public function editLogbookEntry(LogbookEntry $entry)
    {
        $user = auth()->user();
        $student = $user->student;

        if ($entry->fieldPlacement->student_id !== $student->id || $entry->status !== 'draft') {
            abort(403);
        }

        return view('student.elogbook.edit', compact('entry'));
    }

    /**
     * Update logbook entry
     */
    public function updateLogbookEntry(Request $request, LogbookEntry $entry)
    {
        $user = auth()->user();
        $student = $user->student;

        if ($entry->fieldPlacement->student_id !== $student->id) {
            abort(403);
        }

        $validated = $request->validate([
            'activity_date' => 'required|date',
            'activity_description' => 'required|string',
            'skills_learned' => 'nullable|string',
            'challenges' => 'nullable|string',
            'solutions' => 'nullable|string',
            'hours_worked' => 'required|integer|min:1|max:24',
        ]);

        try {
            $entry = $this->logbookService->updateLogbookEntry($entry, $validated);
            return redirect()->route('student.elogbook.show', $entry->id)
                ->with('success', 'Logbook entry updated');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Submit logbook entry
     */
    public function submitLogbookEntry(LogbookEntry $entry)
    {
        $user = auth()->user();
        $student = $user->student;

        if ($entry->fieldPlacement->student_id !== $student->id) {
            abort(403);
        }

        try {
            $this->logbookService->submitLogbookEntry($entry);
            return redirect()->route('student.elogbook.show', $entry->id)
                ->with('success', 'Logbook entry submitted for approval');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Weekly reports
     */
    public function weeklyReports()
    {
        $user = auth()->user();
        $student = $user->student;

        $fieldPlacement = $student->fieldPlacements()
            ->where('status', 'active')
            ->first();

        if (!$fieldPlacement) {
            return redirect()->route('student.dashboard')
                ->with('warning', 'No active field placement');
        }

        $reports = $fieldPlacement->weeklyReports()
            ->orderBy('week_number', 'desc')
            ->get();

        return view('student.weekly-reports.index', compact('fieldPlacement', 'reports'));
    }

    /**
     * Class attendance
     */
    public function classAttendance()
    {
        $user = auth()->user();
        $student = $user->student;

        $attendances = ClassAttendance::with('classSession.course')
            ->where('student_id', $student->id)
            ->orderBy('marked_at', 'desc')
            ->paginate(15);

        $summary = $this->attendanceService->getStudentOverallAttendance(
            $student->id,
            session('current_semester_id') ?? 1
        );

        return view('student.class-attendance.index', compact('attendances', 'summary'));
    }

    /**
     * Mark class attendance with code
     */
    public function markAttendanceCode(Request $request)
    {
        $validated = $request->validate([
            'attendance_code' => 'required|string',
        ]);

        $user = auth()->user();
        $student = $user->student;

        $session = ClassSession::where('attendance_code', $validated['attendance_code'])
            ->where('status', 'open')
            ->first();

        if (!$session) {
            return back()->withErrors(['attendance_code' => 'Invalid or expired attendance code']);
        }

        try {
            $this->attendanceService->markAttendance($session, $student->id, 'code');
            return redirect()->route('student.class-attendance')
                ->with('success', 'Attendance marked successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Notifications
     */
    public function notifications()
    {
        $user = auth()->user();
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('student.notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markNotificationRead(Notification $notification)
    {
        $user = auth()->user();

        if ($notification->user_id !== $user->id) {
            abort(403);
        }

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Show Field Placement GPS Attendance page
     */
    public function fieldAttendance()
    {
        $user = auth()->user();
        $student = $user->student;

        $placement = $student ? $student->fieldPlacements()->where('status', 'active')->first() : null;

        if (!$placement) {
            return redirect()->route('student.dashboard')
                ->with('warning', 'You do not have an active field placement.');
        }

        $attendances = $placement->fieldAttendances()
            ->orderBy('attendance_date', 'desc')
            ->paginate(15);

        $todayAttendance = $placement->fieldAttendances()
            ->where('attendance_date', today()->toDateString())
            ->first();

        $summary = $this->fieldService->getFieldAttendanceSummary($placement);

        return view('student.field-attendance.index', compact('placement', 'attendances', 'todayAttendance', 'summary'));
    }

    /**
     * Handle student GPS Check-in (Strict Anti-Cheat Verification)
     */
    public function checkInFieldAttendance(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();
        $student = $user->student;

        $placement = $student ? $student->fieldPlacements()->where('status', 'active')->first() : null;

        if (!$placement) {
            return back()->withErrors(['error' => 'No active field placement found.']);
        }

        // Anti-Cheat 1: Reject rough/spoofed coordinates with very poor accuracy (> 250m)
        if ($request->filled('accuracy') && (float)$request->input('accuracy') > 250) {
            return back()->withErrors([
                'location' => 'Ishara ya GPS ni hafifu mno (&plusmn;' . round($request->input('accuracy')) . 'm). Tafadhali toka nje au simama karibu na dirisha ili setilaiti ya GPS inase eneo lako vizuri.'
            ])->withInput();
        }

        $studentLat = (float) $request->input('latitude');
        $studentLon = (float) $request->input('longitude');

        // Anti-Cheat 2: Check server-side Haversine geofence strictly
        $geofence = $this->fieldService->verifyGeofence($placement, $studentLat, $studentLon);

        if (!$geofence['within_geofence']) {
            return back()->withErrors([
                'location' => $geofence['message']
            ])->withInput();
        }

        $today = today()->toDateString();
        $existing = $placement->fieldAttendances()->where('attendance_date', $today)->first();

        if ($existing && $existing->check_in_time) {
            return back()->with('info', 'Tayari umesharekodi mahudhurio ya leo saa ' . $existing->check_in_time);
        }

        $now = now();
        $status = $now->format('H:i') > '09:00' ? 'late' : 'present';

        $auditNote = "[GPS: {$geofence['distance_formatted']} kutoka {$geofence['org_name']} - IP: {$request->ip()}]";
        if ($request->filled('notes')) {
            $auditNote .= " " . $request->input('notes');
        }

        $this->fieldService->recordFieldAttendance($placement, [
            'attendance_date' => $today,
            'check_in_time' => $now->toTimeString(),
            'status' => $status,
            'latitude' => (string) $studentLat,
            'longitude' => (string) $studentLon,
            'notes' => $auditNote,
        ]);

        return redirect()->route('student.field-attendance')
            ->with('success', 'Mahudhurio yamethibitishwa na kurekodiwa kikamilifu! (' . $geofence['message'] . ')');
    }

    /**
     * Show Field Placement Details
     */
    public function fieldPlacementDetails()
    {
        $student = auth()->user()->student;
        $placement = $student ? $student->fieldPlacements()->with(['hostOrganization', 'supervisorAssignments.supervisor.user'])->first() : null;

        return view('student.field-placement.index', compact('student', 'placement'));
    }

    /**
     * Show Field Self-Application Form
     */
    public function showFieldApplicationForm()
    {
        $student = auth()->user()->student;
        $existingPlacement = $student ? $student->fieldPlacements()->first() : null;

        return view('student.field-placement.apply', compact('student', 'existingPlacement'));
    }

    /**
     * Store Student Self-Applied Field Placement with Accurate GPS Coordinates
     */
    public function storeFieldApplication(Request $request)
    {
        $request->validate([
            'organization_name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'geofence_radius_meters' => 'nullable|integer|min:50|max:1000',
            'organization_phone' => 'nullable|string|max:25',
            'organization_email' => 'nullable|email|max:255',
            'supervisor_name' => 'required|string|max:255',
            'supervisor_phone' => 'required|string|max:25',
            'supervisor_title' => 'nullable|string|max:150',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_days' => 'required|integer|min:1|max:365',
        ]);

        $student = auth()->user()->student;
        if (!$student) {
            return back()->withErrors(['error' => 'Student record not found.']);
        }

        $lat = $request->filled('latitude') ? (float)$request->input('latitude') : null;
        $lon = $request->filled('longitude') ? (float)$request->input('longitude') : null;
        $radius = $request->filled('geofence_radius_meters') ? (int)$request->input('geofence_radius_meters') : 200;

        // Auto fallback to accurate city coordinates if not supplied
        if (empty($lat) || empty($lon)) {
            $cityCoords = \App\Models\HostOrganization::getCityDefaultCoordinates($request->input('city'));
            $lat = $cityCoords['lat'];
            $lon = $cityCoords['lon'];
        }

        // 1. Create or find host organization
        $hostOrg = \App\Models\HostOrganization::firstOrCreate(
            ['name' => trim($request->input('organization_name'))],
            [
                'industry' => $request->input('industry') ?: 'Private/Public Sector',
                'city' => $request->input('city'),
                'address' => $request->input('address'),
                'latitude' => $lat,
                'longitude' => $lon,
                'geofence_radius_meters' => $radius,
                'phone' => $request->input('organization_phone') ?: $request->input('supervisor_phone'),
                'email' => $request->input('organization_email'),
                'contact_person' => $request->input('supervisor_name'),
                'contact_title' => $request->input('supervisor_title') ?: 'Field Supervisor',
                'is_active' => true,
            ]
        );

        // Always update coordinates, radius, and supervisor details
        $hostOrg->update([
            'latitude' => $lat,
            'longitude' => $lon,
            'geofence_radius_meters' => $radius,
            'contact_person' => $request->input('supervisor_name'),
            'phone' => $request->input('supervisor_phone'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
        ]);

        $academicYear = \App\Models\AcademicYear::where('is_current', true)->first()
            ?? \App\Models\AcademicYear::first();

        // 2. Create or update field placement for this student
        $placement = \App\Models\FieldPlacement::updateOrCreate(
            ['student_id' => $student->id],
            [
                'host_organization_id' => $hostOrg->id,
                'academic_year_id' => $academicYear ? $academicYear->id : null,
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'total_days' => $request->input('total_days'),
                'days_completed' => 0,
                'field_progress' => 0,
                'status' => 'active',
                'notes' => 'Mwanafunzi amejaza taarifa za eneo lake la field. Msimamizi wa Eneo la Kazi: '
                    . $request->input('supervisor_name') . ' (Simu: ' . $request->input('supervisor_phone') . '). GPS: '
                    . round($lat, 5) . ', ' . round($lon, 5) . ' (Upeo: ' . $radius . 'm).',
            ]
        );

        return redirect()->route('student.dashboard')
            ->with('success', 'Taarifa za eneo la Field na Alama za GPS (Lat: ' . round($lat, 4) . ', Lon: ' . round($lon, 4) . ') zimehifadhiwa kikamilifu!');
    }
}

