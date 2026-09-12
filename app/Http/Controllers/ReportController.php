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
     * Export report to PDF
     */
    protected function exportPDF(array $data, string $filename)
    {
        // This would use a package like barryvdh/laravel-dompdf
        // For now, return a JSON response indicating PDF generation
        
        // In production, you would use:
        // $pdf = PDF::loadView('exports.report-pdf', $data);
        // return $pdf->download($filename . '.pdf');

        return response()->json([
            'message' => 'PDF export would be generated here',
            'filename' => $filename . '.pdf',
            'data' => $data,
        ]);
    }

    /**
     * Export report to Excel
     */
    protected function exportExcel(array $data, string $filename)
    {
        // This would use a package like laravel/excel (Maatwebsite)
        // For now, return a JSON response indicating Excel generation
        
        // In production, you would use:
        // return Excel::download(new ReportExport($data), $filename . '.xlsx');

        return response()->json([
            'message' => 'Excel export would be generated here',
            'filename' => $filename . '.xlsx',
            'data' => $data,
        ]);
    }
}
