<x-layouts.cbe title="Field Placements - CBE Portal">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Field Placements & Supervisor Allocations</h1>
                <p class="text-xs text-slate-500 mt-1">Manage industrial training placements, GPS geofences, and assign university field supervisors.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            @if($placements->isEmpty())
                <p class="text-xs text-slate-400 py-10 text-center">No field placements found.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Student</th>
                                <th class="px-4 py-3">Host Organization</th>
                                <th class="px-4 py-3">GPS Location</th>
                                <th class="px-4 py-3">Geofence Radius</th>
                                <th class="px-4 py-3">Assigned Supervisor</th>
                                <th class="px-4 py-3">Progress</th>
                                <th class="px-4 py-3 text-right">Assign Supervisor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($placements as $pl)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-900">
                                        {{ $pl->student->user->name ?? 'Student' }}
                                        <span class="block text-[11px] text-slate-400">{{ $pl->student->user->registration_number ?? '' }}</span>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-slate-800">
                                        {{ $pl->hostOrganization->name ?? 'Host' }}
                                        <span class="block text-[11px] text-slate-400">{{ $pl->hostOrganization->city ?? '' }}</span>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-slate-600">
                                        @if($pl->hostOrganization && $pl->hostOrganization->latitude)
                                            <span class="text-emerald-700 font-semibold">
                                                <i class="fa-solid fa-location-dot text-emerald-500 mr-1"></i>
                                                {{ round($pl->hostOrganization->latitude, 4) }}, {{ round($pl->hostOrganization->longitude, 4) }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">Not set</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-emerald-600">
                                        &plusmn;{{ $pl->hostOrganization->geofence_radius_meters ?? 300 }}m
                                    </td>
                                    <td class="px-4 py-3">
                                        @php 
                                            $activeAssignment = $pl->supervisorAssignments->where('status', 'active')->first();
                                        @endphp
                                        @if($activeAssignment && $activeAssignment->supervisor)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                <i class="fa-solid fa-user-check mr-1.5 text-indigo-500"></i>
                                                {{ $activeAssignment->supervisor->user->name ?? 'Supervisor' }}
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase">
                                                Unassigned
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-bold text-slate-900">{{ $pl->field_progress }}%</span>
                                            <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $pl->field_progress }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <form method="POST" action="{{ route('admin.field-placements.assign-supervisor', $pl->id) }}" class="inline-flex items-center space-x-1.5">
                                            @csrf
                                            <select name="supervisor_staff_id" required class="px-2.5 py-1.5 text-xs rounded-lg border border-slate-300 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                <option value="">Select Supervisor...</option>
                                                @foreach($supervisors as $sup)
                                                    <option value="{{ $sup->id }}" {{ ($activeAssignment && $activeAssignment->supervisor_staff_id == $sup->id) ? 'selected' : '' }}>
                                                        {{ $sup->user->name }} ({{ $sup->designation ?? 'Supervisor' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition">
                                                Assign
                                            </button>
                                        </form>
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
