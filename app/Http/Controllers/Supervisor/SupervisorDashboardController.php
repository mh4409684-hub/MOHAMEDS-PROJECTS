<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\LogbookEntry;
use App\Models\WeeklyReport;
use App\Models\FieldPlacement;
use App\Models\Notification;
use App\Services\LogbookService;
use App\Services\FieldManagementService;
use Illuminate\Http\Request;

class SupervisorDashboardController extends Controller
{
    protected LogbookService $logbookService;
    protected FieldManagementService $fieldService;

    public function __construct(
        LogbookService $logbookService,
        FieldManagementService $fieldService
    ) {
        $this->logbookService = $logbookService;
        $this->fieldService = $fieldService;
    }

    /**
     * Get supervisor staff profile with null-safe fallback
     */
    protected function getSupervisorStaff()
    {
        $user = auth()->user();
        $staff = $user->staff;
        if (!$staff) {
            $staff = \App\Models\Staff::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'campus_id' => $user->campus_id ?? 1,
                    'staff_type' => 'supervisor',
                    'designation' => 'Field Supervisor',
                    'employment_date' => now(),
                    'employment_status' => 'active',
                ]
            );
        }
        return $staff;
    }

    /**
     * Show supervisor dashboard
     */
    public function index()
    {
        $user = auth()->user();
        $supervisor = $this->getSupervisorStaff();

        $assignedPlacements = $supervisor->fieldSupervisorAssignments()
            ->where('status', 'active')
            ->with('fieldPlacement.student.user')
            ->get();

        $pendingLogbooks = LogbookEntry::whereHas('fieldPlacement.supervisorAssignments', function ($q) use ($supervisor) {
            $q->where('supervisor_staff_id', $supervisor->id)
              ->where('status', 'active');
        })
        ->where('status', 'submitted')
        ->with('fieldPlacement.student.user')
        ->latest('submitted_at')
        ->limit(10)
        ->get();

        $pendingWeeklyReports = WeeklyReport::whereHas('fieldPlacement.supervisorAssignments', function ($q) use ($supervisor) {
            $q->where('supervisor_staff_id', $supervisor->id)
              ->where('status', 'active');
        })
        ->where('status', 'submitted')
        ->with('fieldPlacement.student.user')
        ->latest('submitted_at')
        ->limit(10)
        ->get();

        $stats = [
            'assigned_students' => $assignedPlacements->count(),
            'pending_logbooks' => $pendingLogbooks->count(),
            'pending_reports' => $pendingWeeklyReports->count(),
            'total_reviewed' => $supervisor->supervisorReviews()->count(),
        ];

        return view('supervisor.dashboard', compact('stats', 'assignedPlacements', 'pendingLogbooks', 'pendingWeeklyReports'));
    }

    /**
     * Show assigned students
     */
    public function assignedStudents()
    {
        $user = auth()->user();
        $supervisor = $this->getSupervisorStaff();

        $assignments = $supervisor->fieldSupervisorAssignments()
            ->where('status', 'active')
            ->with('fieldPlacement.student.user', 'fieldPlacement.hostOrganization')
            ->latest()
            ->paginate(15);

        return view('supervisor.students.index', compact('assignments'));
    }

    /**
     * Show student details
     */
    public function showStudent(FieldPlacement $placement)
    {
        $user = auth()->user();
        $supervisor = $this->getSupervisorStaff();

        // Verify supervisor is assigned to this placement
        $assignment = $placement->supervisorAssignments()
            ->where('supervisor_staff_id', $supervisor->id)
            ->where('status', 'active')
            ->first();

        if (!$assignment) {
            abort(403);
        }

        $placement->load('student.user', 'hostOrganization', 'logbookEntries', 'weeklyReports', 'fieldAttendances');

        $logbookStats = $this->logbookService->getLogbookStatistics($placement);
        $attendanceStats = $this->fieldService->getFieldAttendanceSummary($placement);

        return view('supervisor.students.show', compact('placement', 'logbookStats', 'attendanceStats'));
    }

    /**
     * Review logbook entries
     */
    public function pendingLogbooks()
    {
        $user = auth()->user();
        $supervisor = $this->getSupervisorStaff();

        $logbooks = LogbookEntry::whereHas('fieldPlacement.supervisorAssignments', function ($q) use ($supervisor) {
            $q->where('supervisor_staff_id', $supervisor->id)
              ->where('status', 'active');
        })
        ->where('status', 'submitted')
        ->with('fieldPlacement.student.user', 'fieldPlacement.hostOrganization')
        ->latest('submitted_at')
        ->paginate(15);

        return view('supervisor.logbooks.pending', compact('logbooks'));
    }

    /**
     * Show logbook for review
     */
    public function reviewLogbook(LogbookEntry $entry)
    {
        $user = auth()->user();
        $supervisor = $this->getSupervisorStaff();

        // Verify supervisor is assigned
        $assignment = $entry->fieldPlacement->supervisorAssignments()
            ->where('supervisor_staff_id', $supervisor->id)
            ->where('status', 'active')
            ->first();

        if (!$assignment || $entry->status !== 'submitted') {
            abort(403);
        }

        $entry->load('fieldPlacement.student.user', 'fieldPlacement.hostOrganization');

        return view('supervisor.logbooks.review', compact('entry'));
    }

    /**
     * Approve logbook
     */
    public function approveLogbook(Request $request, LogbookEntry $entry)
    {
        $user = auth()->user();
        $supervisor = $this->getSupervisorStaff();

        // Verify supervisor is assigned
        $assignment = $entry->fieldPlacement->supervisorAssignments()
            ->where('supervisor_staff_id', $supervisor->id)
            ->where('status', 'active')
            ->first();

        if (!$assignment) {
            abort(403);
        }

        $validated = $request->validate([
            'comments' => 'nullable|string',
        ]);

        try {
            $this->logbookService->approveLogbookEntry(
                $entry,
                $supervisor->id,
                $validated['comments'] ?? null,
                'DIGITAL_SIGNATURE_' . now()->timestamp // Placeholder signature
            );

            return redirect()->route('supervisor.logbooks.pending')
                ->with('success', 'Logbook approved successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Reject logbook
     */
    public function rejectLogbook(Request $request, LogbookEntry $entry)
    {
        $user = auth()->user();
        $supervisor = $this->getSupervisorStaff();

        // Verify supervisor is assigned
        $assignment = $entry->fieldPlacement->supervisorAssignments()
            ->where('supervisor_staff_id', $supervisor->id)
            ->where('status', 'active')
            ->first();

        if (!$assignment) {
            abort(403);
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        try {
            $this->logbookService->rejectLogbookEntry(
                $entry,
                $supervisor->id,
                $validated['rejection_reason']
            );

            return redirect()->route('supervisor.logbooks.pending')
                ->with('success', 'Logbook rejected');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Review weekly reports
     */
    public function pendingReports()
    {
        $user = auth()->user();
        $supervisor = $this->getSupervisorStaff();

        $reports = WeeklyReport::whereHas('fieldPlacement.supervisorAssignments', function ($q) use ($supervisor) {
            $q->where('supervisor_staff_id', $supervisor->id)
              ->where('status', 'active');
        })
        ->where('status', 'submitted')
        ->with('fieldPlacement.student.user')
        ->latest('submitted_at')
        ->paginate(15);

        return view('supervisor.reports.pending', compact('reports'));
    }

    /**
     * Show weekly report for review
     */
    public function reviewReport(WeeklyReport $report)
    {
        $user = auth()->user();
        $supervisor = $this->getSupervisorStaff();

        // Verify supervisor is assigned
        $assignment = $report->fieldPlacement->supervisorAssignments()
            ->where('supervisor_staff_id', $supervisor->id)
            ->where('status', 'active')
            ->first();

        if (!$assignment || $report->status !== 'submitted') {
            abort(403);
        }

        $report->load('fieldPlacement.student.user');

        return view('supervisor.reports.review', compact('report'));
    }

    /**
     * Approve weekly report
     */
    public function approveReport(Request $request, WeeklyReport $report)
    {
        $user = auth()->user();
        $supervisor = $this->getSupervisorStaff();

        // Verify supervisor is assigned
        $assignment = $report->fieldPlacement->supervisorAssignments()
            ->where('supervisor_staff_id', $supervisor->id)
            ->where('status', 'active')
            ->first();

        if (!$assignment) {
            abort(403);
        }

        $validated = $request->validate([
            'comments' => 'nullable|string',
        ]);

        try {
            $this->logbookService->approveWeeklyReport($report, $supervisor->id, $validated['comments'] ?? null);
            return redirect()->route('supervisor.reports.pending')
                ->with('success', 'Weekly report approved');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * View logbook history
     */
    public function logbookHistory(FieldPlacement $placement)
    {
        $user = auth()->user();
        $supervisor = $this->getSupervisorStaff();

        // Verify supervisor is assigned
        $assignment = $placement->supervisorAssignments()
            ->where('supervisor_staff_id', $supervisor->id)
            ->first();

        if (!$assignment) {
            abort(403);
        }

        $entries = $this->logbookService->getLogbookEntries($placement);

        return view('supervisor.logbooks.history', compact('placement', 'entries'));
    }

    /**
     * Field attendance summary
     */
    public function fieldAttendance(FieldPlacement $placement)
    {
        $user = auth()->user();
        $supervisor = $this->getSupervisorStaff();

        // Verify supervisor is assigned
        $assignment = $placement->supervisorAssignments()
            ->where('supervisor_staff_id', $supervisor->id)
            ->where('status', 'active')
            ->first();

        if (!$assignment) {
            abort(403);
        }

        $attendance = $placement->fieldAttendances()
            ->orderBy('attendance_date', 'desc')
            ->paginate(20);

        $summary = $this->fieldService->getFieldAttendanceSummary($placement);

        return view('supervisor.attendance.field', compact('placement', 'attendance', 'summary'));
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

        return view('supervisor.notifications.index', compact('notifications'));
    }
}
