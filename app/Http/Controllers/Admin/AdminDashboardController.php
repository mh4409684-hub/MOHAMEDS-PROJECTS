<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Staff;
use App\Models\FieldPlacement;
use App\Models\LogbookEntry;
use App\Models\ClassSession;
use App\Models\Campus;
use App\Models\Department;
use App\Models\Programme;
use App\Models\Course;
use App\Models\Section;
use App\Models\AcademicYear;
use App\Services\StudentService;
use App\Services\StaffService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    protected StudentService $studentService;
    protected StaffService $staffService;

    public function __construct(StudentService $studentService, StaffService $staffService)
    {
        $this->studentService = $studentService;
        $this->staffService = $staffService;
    }

    /**
     * Show admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'active_students' => Student::where('enrollment_status', 'active')->count(),
            'total_staff' => Staff::count(),
            'total_field_students' => FieldPlacement::where('status', 'active')->count(),
            'pending_logbooks' => LogbookEntry::where('status', 'submitted')->count(),
            'approved_logbooks' => LogbookEntry::where('status', 'approved')->count(),
            'todays_classes' => ClassSession::where('session_date', today())->count(),
            'total_campuses' => Campus::count(),
            'total_programmes' => Programme::count(),
            'total_courses' => Course::count(),
        ];

        $recentStudents = Student::with('user', 'programme')
            ->latest()
            ->limit(10)
            ->get();

        $recentStaff = Staff::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $pendingApprovals = LogbookEntry::with('fieldPlacement.student.user')
            ->where('status', 'submitted')
            ->orderBy('submitted_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentStudents', 'recentStaff', 'pendingApprovals'));
    }

    /**
     * Student management
     */
    public function students(Request $request)
    {
        $query = Student::with('user', 'programme', 'section', 'campus');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('campus')) {
            $query->where('campus_id', $request->input('campus'));
        }

        if ($request->filled('programme')) {
            $query->where('programme_id', $request->input('programme'));
        }

        if ($request->filled('status')) {
            $query->where('enrollment_status', $request->input('status'));
        }

        $students = $query->paginate(15);
        $campuses = Campus::all();
        $programmes = Programme::all();

        return view('admin.students.index', compact('students', 'campuses', 'programmes'));
    }

    /**
     * Create student form
     */
    public function createStudentForm()
    {
        $campuses = Campus::all();
        $programmes = Programme::all();

        return view('admin.students.create', compact('campuses', 'programmes'));
    }

    /**
     * Store new student
     */
    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'registration_number' => 'required|string|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string',
            'programme_id' => 'required|exists:programmes,id',
            'section_id' => 'required|exists:sections,id',
            'campus_id' => 'required|exists:campuses,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'year_of_study' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        try {
            $student = $this->studentService->createStudent($validated);
            return redirect()->route('admin.students.show', $student->id)
                ->with('success', 'Student created successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show student details
     */
    public function showStudent(Student $student)
    {
        $student->load('user', 'programme', 'section', 'campus', 'enrollments', 'fieldPlacements');

        return view('admin.students.show', compact('student'));
    }

    /**
     * Edit student
     */
    public function editStudent(Student $student)
    {
        $campuses = Campus::all();
        $programmes = Programme::all();
        $sections = Section::all();

        return view('admin.students.edit', compact('student', 'campuses', 'programmes', 'sections'));
    }

    /**
     * Update student
     */
    public function updateStudent(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'year_of_study' => 'required|integer|min:1',
            'section_id' => 'required|exists:sections,id',
            'enrollment_status' => 'required|in:active,suspended,graduated,withdrawn',
            'notes' => 'nullable|string',
        ]);

        try {
            $student = $this->studentService->updateStudent($student, $validated);
            return redirect()->route('admin.students.show', $student->id)
                ->with('success', 'Student updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Staff management
     */
    public function staff(Request $request)
    {
        $query = Staff::with('user', 'campus', 'department');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('staff_type', $request->input('type'));
        }

        if ($request->filled('campus')) {
            $query->where('campus_id', $request->input('campus'));
        }

        $staff = $query->paginate(15);
        $campuses = Campus::all();

        return view('admin.staff.index', compact('staff', 'campuses'));
    }

    /**
     * Create staff form
     */
    public function createStaffForm()
    {
        $campuses = Campus::all();
        $departments = Department::all();

        return view('admin.staff.create', compact('campuses', 'departments'));
    }

    /**
     * Store new staff
     */
    public function storeStaff(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string',
            'staff_type' => 'required|in:lecturer,supervisor,coordinator,admin',
            'designation' => 'nullable|string',
            'campus_id' => 'required|exists:campuses,id',
            'department_id' => 'nullable|exists:departments,id',
            'bio' => 'nullable|string',
            'office_location' => 'nullable|string',
        ]);

        try {
            $staff = $this->staffService->createStaff($validated);
            return redirect()->route('admin.staff.show', $staff->id)
                ->with('success', 'Staff member created successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show staff details
     */
    public function showStaff(Staff $staff)
    {
        $staff->load('user', 'campus', 'department', 'fieldSupervisorAssignments', 'classSessions');

        return view('admin.staff.show', compact('staff'));
    }

    /**
     * Academic management
     */
    public function academicSettings()
    {
        $campuses = Campus::all();
        $departments = Department::all();
        $programmes = Programme::all();
        $academicYears = AcademicYear::all();
        $courses = Course::all();

        return view('admin.academic.index', compact('campuses', 'departments', 'programmes', 'academicYears', 'courses'));
    }

    /**
     * Field placements report
     */
    public function fieldPlacements(Request $request)
    {
        $query = FieldPlacement::with(['student.user', 'hostOrganization', 'academicYear', 'supervisorAssignments.supervisor.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('campus')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('campus_id', $request->input('campus'));
            });
        }

        $placements = $query->paginate(15);
        $campuses = Campus::all();
        $supervisors = Staff::with('user')->whereIn('staff_type', ['supervisor', 'lecturer', 'admin'])->get();

        return view('admin.field-placements.index', compact('placements', 'campuses', 'supervisors'));
    }

    /**
     * Assign supervisor to field placement
     */
    public function assignSupervisorToPlacement(Request $request, FieldPlacement $placement)
    {
        $request->validate([
            'supervisor_staff_id' => 'required|exists:staff,id',
        ]);

        // Deactivate previous
        $placement->supervisorAssignments()->where('status', 'active')->update([
            'status' => 'reassigned',
            'unassigned_date' => now(),
        ]);

        \App\Models\FieldSupervisorAssignment::create([
            'field_placement_id' => $placement->id,
            'supervisor_staff_id' => $request->input('supervisor_staff_id'),
            'assigned_date' => now(),
            'status' => 'active',
        ]);

        return back()->with('success', 'Field supervisor assigned successfully! The student now appears immediately in the supervisor\'s account.');
    }

    /**
     * System reports
     */
    public function reports()
    {
        return view('admin.reports.index');
    }

    /**
     * Attendance reports
     */
    public function attendanceReports(Request $request)
    {
        $courses = Course::all();
        $sections = Section::all();

        if ($request->has('generate')) {
            $course = Course::find($request->input('course_id'));
            $section = Section::find($request->input('section_id'));

            if ($course && $section) {
                $sessions = ClassSession::where('course_id', $course->id)
                    ->where('section_id', $section->id)
                    ->with('attendances.student.user')
                    ->get();

                return view('admin.reports.attendance', compact('course', 'section', 'sessions'));
            }
        }

        return view('admin.reports.attendance-filter', compact('courses', 'sections'));
    }
}
