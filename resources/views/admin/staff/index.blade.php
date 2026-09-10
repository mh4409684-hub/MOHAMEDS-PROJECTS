<x-layouts.cbe title="Staff Directory - CBE Portal">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Academic & Field Staff</h1>
                <p class="text-xs text-slate-500 mt-1">Supervisors, lecturers, and coordinators.</p>
            </div>
            <a href="{{ route('admin.staff.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow transition">
                <i class="fa-solid fa-user-plus mr-1.5"></i> Add Staff Member
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            @if($staff->isEmpty())
                <p class="text-xs text-slate-400 py-10 text-center">No staff members found.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Role / Type</th>
                                <th class="px-4 py-3">Campus</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($staff as $st)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-900">{{ $st->user->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-500">{{ $st->user->email ?? '-' }}</td>
                                    <td class="px-4 py-3 capitalize font-medium text-slate-800">{{ $st->staff_type }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $st->campus->name ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold uppercase text-[10px]">
                                            {{ $st->employment_status }}
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
