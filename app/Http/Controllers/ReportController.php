<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\FieldPlacement;
use App\Models\Course;
use App\Models\Section;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Show report generation interface
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Generate student profile report
     */
    public function studentProfile(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'format' => 'required|in:view,pdf,excel',
        ]);

        $student = Student::findOrFail($validated['student_id']);
        $reportData = $this->reportService->generateStudentReport($student);

        if ($validated['format'] === 'view') {
            return view('reports.student-profile', ['report' => $reportData]);
        } elseif ($validated['format'] === 'pdf') {
            return $this->exportPDF($reportData, 'student-profile-' . $student->id);
        } elseif ($validated['format'] === 'excel') {
            return $this->exportExcel($reportData, 'student-profile-' . $student->id);
        }
    }

    /**
     * Generate attendance report
     */
    public function attendance(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'section_id' => 'required|exists:sections,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'format' => 'required|in:view,pdf,excel',
        ]);

        $reportData = $this->reportService->generateAttendanceReport(
            $validated['course_id'],
            $validated['section_id'],
            $validated['start_date'] ?? null,
            $validated['end_date'] ?? null
        );

        if ($validated['format'] === 'view') {
            return view('reports.attendance', ['report' => $reportData]);
        } elseif ($validated['format'] === 'pdf') {
            return $this->exportPDF($reportData, 'attendance-report-' . now()->format('Y-m-d'));
        } elseif ($validated['format'] === 'excel') {
            return $this->exportExcel($reportData, 'attendance-report-' . now()->format('Y-m-d'));
        }
    }

    /**
     * Generate field placement report
     */
    public function fieldPlacement(Request $request)
    {
        $validated = $request->validate([
            'placement_id' => 'required|exists:field_placements,id',
            'format' => 'required|in:view,pdf,excel',
        ]);

        $placement = FieldPlacement::findOrFail($validated['placement_id']);
        $reportData = $this->reportService->generateFieldPlacementReport($placement);

        if ($validated['format'] === 'view') {
            return view('reports.field-placement', ['report' => $reportData]);
        } elseif ($validated['format'] === 'pdf') {
            return $this->exportPDF($reportData, 'field-placement-' . $placement->student->user->registration_number);
        } elseif ($validated['format'] === 'excel') {
            return $this->exportExcel($reportData, 'field-placement-' . $placement->student->user->registration_number);
        }
    }

    /**
     * Generate system statistics report
     */
    public function systemStatistics(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|in:view,pdf,excel',
        ]);

        if (!auth()->user()->hasRole(['admin', 'super_admin'])) {
            abort(403, 'Unauthorized access to system statistics');
        }

        $reportData = $this->reportService->generateSystemStatistics();

        if ($validated['format'] === 'view') {
            return view('reports.system-statistics', ['report' => $reportData]);
        } elseif ($validated['format'] === 'pdf') {
            return $this->exportPDF($reportData, 'system-statistics-' . now()->format('Y-m-d'));
        } elseif ($validated['format'] === 'excel') {
            return $this->exportExcel($reportData, 'system-statistics-' . now()->format('Y-m-d'));
        }
    }

    /**
     * Export report to PDF / Printable View
     */
    protected function exportPDF(array $data, string $filename)
    {
        return response()->view('exports.report-pdf', [
            'report' => $data,
            'title' => $data['title'] ?? 'CBE Official Report',
            'filename' => $filename,
            'autoPrint' => true,
        ]);
    }

    /**
     * Export report to CSV/Excel Spreadsheet
     */
    protected function exportExcel(array $data, string $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($data) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM so Microsoft Excel correctly displays Swahili and special characters
            fputs($handle, "\xEF\xBB\xBF");

            // Header information
            fputcsv($handle, ['COLLEGE OF BUSINESS EDUCATION (CBE)']);
            fputcsv($handle, ['Field Practical Training & Academic Management System']);
            fputcsv($handle, [$data['title'] ?? 'Official System Report']);
            fputcsv($handle, ['Generated At', $data['generated_at'] ?? now()->toDateTimeString()]);
            fputcsv($handle, []);

            // Loop through report sections
            foreach ($data as $sectionKey => $sectionVal) {
                if (in_array($sectionKey, ['title', 'generated_at'])) continue;

                if (is_array($sectionVal)) {
                    // Check if numeric list of rows (e.g. logbook_entries, student_attendance)
                    if (isset($sectionVal[0]) && is_array($sectionVal[0])) {
                        fputcsv($handle, ['--- ' . strtoupper(str_replace('_', ' ', $sectionKey)) . ' ---']);
                        $columns = array_keys(array_filter($sectionVal[0], fn($v) => !is_array($v)));
                        fputcsv($handle, array_map(fn($c) => ucwords(str_replace('_', ' ', $c)), $columns));
                        foreach ($sectionVal as $row) {
                            $rowVals = [];
                            foreach ($columns as $c) {
                                $rowVals[] = $row[$c] ?? '';
                            }
                            fputcsv($handle, $rowVals);
                        }
                        fputcsv($handle, []);
                    } else {
                        // Key-value pairs
                        fputcsv($handle, ['--- ' . strtoupper(str_replace('_', ' ', $sectionKey)) . ' ---']);
                        foreach ($sectionVal as $k => $v) {
                            if (!is_array($v)) {
                                fputcsv($handle, [ucwords(str_replace('_', ' ', $k)), $v]);
                            } elseif (is_array($v) && !isset($v[0])) {
                                foreach ($v as $subK => $subV) {
                                    if (!is_array($subV)) {
                                        fputcsv($handle, [ucwords(str_replace('_', ' ', "{$k} - {$subK}")), $subV]);
                                    }
                                }
                            }
                        }
                        fputcsv($handle, []);
                    }
                }
            }

            fclose($handle);
        }, 200, $headers);
    }
}
