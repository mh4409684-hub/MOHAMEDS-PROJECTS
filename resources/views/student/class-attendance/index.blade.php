<x-layouts.cbe title="Class Attendance - CBE Portal">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Lecture Class Attendance</h1>
                <p class="text-xs text-slate-500 mt-0.5">Submit dynamic attendance codes provided by your course instructors.</p>
            </div>
        </div>

        <!-- Code Input Card -->
        <div class="bg-gradient-to-r from-purple-900 via-indigo-900 to-slate-900 p-6 sm:p-8 rounded-2xl text-white shadow-lg">
            <div class="max-w-md mx-auto text-center space-y-4">
                <div class="h-12 w-12 mx-auto rounded-xl bg-white/20 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold">Have an Attendance Code?</h2>
                    <p class="text-xs text-purple-200 mt-1">Codes are valid during active lecture sessions.</p>
                </div>
                <form method="POST" action="{{ route('student.mark-attendance') }}" class="space-y-3">
                    @csrf
                    <input type="text" name="attendance_code" placeholder="ENTER 6-DIGIT CODE" required class="w-full text-center px-4 py-3 bg-white text-slate-900 font-mono font-bold tracking-widest text-lg rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-400">
                    <button type="submit" class="w-full py-3 rounded-xl bg-purple-500 hover:bg-purple-400 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow">
                        Verify Attendance
                    </button>
                </form>
            </div>
        </div>

        <!-- Attendance History -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <h3 class="text-base font-bold text-slate-900 mb-4">Class Session History</h3>
            @if($attendances->isEmpty())
                <p class="text-xs text-slate-400 py-6 text-center">No class attendance recorded yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-2.5">Course</th>
                                <th class="px-4 py-2.5">Date</th>
                                <th class="px-4 py-2.5">Marked At</th>
                                <th class="px-4 py-2.5">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($attendances as $att)
                                <tr>
                                    <td class="px-4 py-2.5 font-bold text-slate-900">
                                        {{ $att->classSession->course->name ?? 'Course' }}
                                    </td>
                                    <td class="px-4 py-2.5 text-slate-500">
                                        {{ $att->classSession->session_date ? $att->classSession->session_date->format('M d, Y') : '' }}
                                    </td>
                                    <td class="px-4 py-2.5 font-mono text-slate-500">
                                        {{ $att->marked_at ? $att->marked_at->format('H:i:s') : '-' }}
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold uppercase text-[10px]">
                                            {{ $att->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layouts.cbe>
