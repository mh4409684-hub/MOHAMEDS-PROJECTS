<?php

namespace App\Services;

use App\Models\LogbookEntry;
use App\Models\WeeklyReport;
use App\Models\SupervisorReview;
use App\Models\FieldPlacement;
use App\Models\Notification;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class LogbookService
{
    /**
     * Create a new logbook entry
     */
    public function createLogbookEntry(array $data): LogbookEntry
    {
        $entry = LogbookEntry::create([
            'field_placement_id' => $data['field_placement_id'],
            'activity_date' => $data['activity_date'],
            'activity_description' => $data['activity_description'],
            'skills_learned' => $data['skills_learned'] ?? null,
            'challenges' => $data['challenges'] ?? null,
            'solutions' => $data['solutions'] ?? null,
            'hours_worked' => $data['hours_worked'] ?? 8,
            'status' => 'draft',
        ]);

        return $entry->load('fieldPlacement.student.user');
    }

    /**
     * Update logbook entry (only in draft status)
     */
    public function updateLogbookEntry(LogbookEntry $entry, array $data): LogbookEntry
    {
        if ($entry->status !== 'draft') {
            throw new \Exception('Cannot edit logbook entry that is not in draft status');
        }

        $entry->update([
            'activity_date' => $data['activity_date'] ?? $entry->activity_date,
            'activity_description' => $data['activity_description'] ?? $entry->activity_description,
            'skills_learned' => $data['skills_learned'] ?? $entry->skills_learned,
            'challenges' => $data['challenges'] ?? $entry->challenges,
            'solutions' => $data['solutions'] ?? $entry->solutions,
            'hours_worked' => $data['hours_worked'] ?? $entry->hours_worked,
        ]);

        return $entry->refresh();
    }

    /**
     * Submit logbook entry for supervisor approval
     */
    public function submitLogbookEntry(LogbookEntry $entry): LogbookEntry
    {
        if ($entry->status !== 'draft') {
            throw new \Exception('Only draft entries can be submitted');
        }

        $entry->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        // Notify supervisors
        $this->notifySuperviors($entry->fieldPlacement, 'logbook_submitted', $entry);

        return $entry->refresh();
    }

    /**
     * Approve logbook entry by supervisor
     */
    public function approveLogbookEntry(LogbookEntry $entry, int $supervisorId, string $comments = null, string $signature = null): LogbookEntry
    {
        if ($entry->status !== 'submitted') {
            throw new \Exception('Only submitted entries can be approved');
        }

        $entry->update([
            'status' => 'approved',
            'supervisor_comments' => $comments,
            'approved_at' => now(),
        ]);

        // Create supervisor review record
        SupervisorReview::create([
            'logbook_entry_id' => $entry->id,
            'supervisor_staff_id' => $supervisorId,
            'review_type' => 'logbook_entry',
            'decision' => 'approved',
            'comments' => $comments,
            'digital_signature' => $signature,
            'reviewed_at' => now(),
        ]);

        // Notify student
        $student = $entry->fieldPlacement->student;
        Notification::create([
            'user_id' => $student->user_id,
            'type' => 'logbook_approved',
            'title' => 'Logbook Entry Approved',
            'message' => 'Your logbook entry for ' . $entry->activity_date->format('Y-m-d') . ' has been approved.',
            'related_logbook_id' => $entry->id,
            'related_student_id' => $student->id,
        ]);

        return $entry->refresh();
    }

    /**
     * Reject logbook entry
     */
    public function rejectLogbookEntry(LogbookEntry $entry, int $supervisorId, string $reason): LogbookEntry
    {
        if ($entry->status !== 'submitted') {
            throw new \Exception('Only submitted entries can be rejected');
        }

        $entry->update([
            'status' => 'rejected',
            'supervisor_rejection_reason' => $reason,
            'rejected_at' => now(),
        ]);

        // Create supervisor review record
        SupervisorReview::create([
            'logbook_entry_id' => $entry->id,
            'supervisor_staff_id' => $supervisorId,
            'review_type' => 'logbook_entry',
            'decision' => 'rejected',
            'rejection_reason' => $reason,
            'reviewed_at' => now(),
        ]);

        // Notify student
        $student = $entry->fieldPlacement->student;
        Notification::create([
            'user_id' => $student->user_id,
            'type' => 'logbook_rejected',
            'title' => 'Logbook Entry Rejected',
            'message' => 'Your logbook entry has been rejected. Reason: ' . $reason,
            'related_logbook_id' => $entry->id,
            'related_student_id' => $student->id,
        ]);

        return $entry->refresh();
    }

    /**
     * Get logbook entries for a field placement
     */
    public function getLogbookEntries(FieldPlacement $placement, string $status = null): Collection
    {
        $query = $placement->logbookEntries();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('activity_date', 'desc')->get();
    }

    /**
     * Create weekly report
     */
    public function createWeeklyReport(array $data): WeeklyReport
    {
        $report = WeeklyReport::create([
            'field_placement_id' => $data['field_placement_id'],
            'week_number' => $data['week_number'],
            'week_start_date' => $data['week_start_date'],
            'week_end_date' => $data['week_end_date'],
            'activities_completed' => $data['activities_completed'] ?? null,
            'skills_acquired' => $data['skills_acquired'] ?? null,
            'challenges' => $data['challenges'] ?? null,
            'solutions' => $data['solutions'] ?? null,
            'summary' => $data['summary'] ?? null,
            'status' => 'draft',
        ]);

        return $report->load('fieldPlacement.student.user');
    }

    /**
     * Update weekly report
     */
    public function updateWeeklyReport(WeeklyReport $report, array $data): WeeklyReport
    {
        if ($report->status !== 'draft') {
            throw new \Exception('Cannot edit weekly report that is not in draft status');
        }

        $report->update([
            'activities_completed' => $data['activities_completed'] ?? $report->activities_completed,
            'skills_acquired' => $data['skills_acquired'] ?? $report->skills_acquired,
            'challenges' => $data['challenges'] ?? $report->challenges,
            'solutions' => $data['solutions'] ?? $report->solutions,
            'summary' => $data['summary'] ?? $report->summary,
        ]);

        return $report->refresh();
    }

    /**
     * Submit weekly report
     */
    public function submitWeeklyReport(WeeklyReport $report): WeeklyReport
    {
        if ($report->status !== 'draft') {
            throw new \Exception('Only draft reports can be submitted');
        }

        $report->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $this->notifySuperviors($report->fieldPlacement, 'weekly_report_submitted', $report);

        return $report->refresh();
    }

    /**
     * Approve weekly report
     */
    public function approveWeeklyReport(WeeklyReport $report, int $supervisorId, string $comments = null): WeeklyReport
    {
        if ($report->status !== 'submitted') {
            throw new \Exception('Only submitted reports can be approved');
        }

        $report->update([
            'status' => 'approved',
            'supervisor_comments' => $comments,
            'approved_at' => now(),
        ]);

        SupervisorReview::create([
            'weekly_report_id' => $report->id,
            'supervisor_staff_id' => $supervisorId,
            'review_type' => 'weekly_report',
            'decision' => 'approved',
            'comments' => $comments,
            'reviewed_at' => now(),
        ]);

        $student = $report->fieldPlacement->student;
        Notification::create([
            'user_id' => $student->user_id,
            'type' => 'weekly_report_approved',
            'title' => 'Weekly Report Approved',
            'message' => 'Your week ' . $report->week_number . ' report has been approved.',
            'related_student_id' => $student->id,
        ]);

        return $report->refresh();
    }

    /**
     * Get student logbook statistics
     */
    public function getLogbookStatistics(FieldPlacement $placement): array
    {
        $entries = $placement->logbookEntries()->get();
        $weekly = $placement->weeklyReports()->get();

        return [
            'total_entries' => $entries->count(),
            'approved_entries' => $entries->where('status', 'approved')->count(),
            'pending_entries' => $entries->where('status', 'submitted')->count(),
            'rejected_entries' => $entries->where('status', 'rejected')->count(),
            'total_hours' => $entries->sum('hours_worked'),
            'weeks_completed' => $weekly->where('status', 'approved')->count(),
            'weeks_submitted' => $weekly->where('status', 'submitted')->count(),
            'last_entry_date' => $entries->max('activity_date'),
        ];
    }

    /**
     * Notify supervisors about submission
     */
    private function notifySuperviors(FieldPlacement $placement, string $type, $data): void
    {
        $supervisors = $placement->supervisorAssignments()
            ->where('status', 'active')
            ->with('supervisor.user')
            ->get();

        foreach ($supervisors as $assignment) {
            Notification::create([
                'user_id' => $assignment->supervisor->user_id,
                'type' => $type,
                'title' => ucfirst(str_replace('_', ' ', $type)),
                'message' => $placement->student->user->name . ' has submitted new ' . str_replace('_', ' ', $type) . '.',
                'related_field_placement_id' => $placement->id,
                'related_student_id' => $placement->student_id,
            ]);
        }
    }
}
