<x-layouts.cbe title="Students Management - CBE Portal">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Student Directory</h1>
                <p class="text-xs text-slate-500 mt-1">Manage enrolled students and review self-registered applications.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.students.pending') }}" class="relative inline-flex items-center px-4 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 font-bold text-xs border border-amber-300 shadow-sm transition">
                    <i class="fa-solid fa-user-clock mr-1.5 text-amber-600"></i> Pending Self-Registrations
                    @if(isset($pendingCount) && $pendingCount > 0)
                        <span class="ml-2 px-2 py-0.5 rounded-full bg-amber-600 text-white text-[10px] font-black">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.students.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow transition">
                    <i class="fa-solid fa-user-plus mr-1.5"></i> Add New Student
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            @if($students->isEmpty())
                <p class="text-xs text-slate-400 py-10 text-center">No students found.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Registration</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Programme</th>
                                <th class="px-4 py-3">Campus</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($students as $st)
                                <tr>
                                    <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $st->user->registration_number ?? '-' }}</td>
                                    <td class="px-4 py-3 font-semibold text-slate-900">{{ $st->user->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-500">{{ $st->user->email ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $st->programme->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $st->campus->name ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold uppercase text-[10px]">
                                            {{ $st->enrollment_status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <a href="{{ route('admin.students.show', $st->id) }}" class="text-blue-600 hover:text-blue-800 font-bold">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.cbe>
