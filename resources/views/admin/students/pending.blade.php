<x-layouts.cbe title="Pending Student Registrations - CBE Portal">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                        &larr; Back to Students
                    </a>
                </div>
                <h1 class="text-2xl font-black text-slate-900 mt-1">Pending Student Self-Registrations</h1>
                <p class="text-xs text-slate-500 mt-0.5">Review prospective student applications. Approving an account activates it and automatically emails the student their activation notice.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 rounded-xl bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold">
                    <i class="fa-solid fa-clock mr-1 text-amber-600"></i> Total Pending: {{ $pendingStudents->total() }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-xl shrink-0"></i>
                    <div>
                        <span class="font-bold text-emerald-950 block text-sm">{{ session('success') }}</span>
                        <span class="text-emerald-700 text-xs">Mwanafunzi sasa anaweza kuingia kwenye mfumo mara moja kwa kutumia email na password yake.</span>
                    </div>
                </div>
                @if(session('approved_student_phone'))
                    @php
                        $msg = urlencode("Habari " . session('approved_student_name') . ", akaunti yako ya mfumo wa CBE (E-Logbook) imeidhinishwa rasmi! Unaweza kuingia sasa hivi kupitia: https://mohamedy-project.onrender.com/cbe/login ukitumia email yako (" . session('approved_student_email') . ") na password uliyoweka.");
                    @endphp
                    <a href="https://wa.me/{{ session('approved_student_phone') }}?text={{ $msg }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition shrink-0">
                        <i class="fa-brands fa-whatsapp text-base mr-2"></i> Mfawamishe WhatsApp
                    </a>
                @endif
            </div>
        @endif

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            @if($pendingStudents->isEmpty())
                <div class="text-center py-16">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">No Pending Applications</h3>
                    <p class="text-xs text-slate-500 mt-1">All self-registered students have been reviewed and approved.</p>
                    <div class="mt-4">
                        <a href="{{ route('admin.students.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                            View All Students
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Registration No</th>
                                <th class="px-4 py-3">Full Name</th>
                                <th class="px-4 py-3">Email Address</th>
                                <th class="px-4 py-3">Programme</th>
                                <th class="px-4 py-3">Campus</th>
                                <th class="px-4 py-3">Registered At</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($pendingStudents as $st)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-4 py-3.5 font-mono font-bold text-slate-900">
                                        {{ $st->user->registration_number ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 font-bold text-slate-900">
                                        {{ $st->user->name ?? '-' }}
                                        <span class="block text-[10px] text-slate-400 font-normal">@ {{ $st->user->username ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <a href="mailto:{{ $st->user->email }}" class="text-blue-600 hover:underline">
                                            {{ $st->user->email ?? '-' }}
                                        </a>
                                        @if($st->user->phone)
                                            <span class="block text-[10px] text-slate-400">{{ $st->user->phone }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-600">
                                        {{ $st->programme->name ?? 'Not Assigned' }}
                                        <span class="block text-[10px] text-slate-400">Year {{ $st->year_of_study ?? '1' }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-600">
                                        {{ $st->campus->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-500">
                                        {{ $st->created_at ? $st->created_at->format('M d, Y H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 text-right space-x-2 whitespace-nowrap">
                                        <!-- Approve Form -->
                                        <form action="{{ route('admin.students.approve', $st->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Je, una uhakika unataka kumkubali mwanafunzi huyu? Atatumiwa email ya uthibitisho moja kwa moja.')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm transition">
                                                <i class="fa-solid fa-check mr-1"></i> Accept & Activate
                                            </button>
                                        </form>

                                        @php
                                            $stPhone = preg_replace('/[^0-9]/', '', $st->user->phone ?? '');
                                            if (str_starts_with($stPhone, '0')) {
                                                $stPhone = '255' . substr($stPhone, 1);
                                            }
                                            $rowMsg = urlencode("Habari " . $st->user->name . ", akaunti yako ya mfumo wa CBE (E-Logbook) imeidhinishwa rasmi! Unaweza kuingia sasa hivi kupitia: https://mohamedy-project.onrender.com/cbe/login ukitumia email yako (" . $st->user->email . ") na password uliyoweka.");
                                        @endphp
                                        @if($stPhone)
                                            <a href="https://wa.me/{{ $stPhone }}?text={{ $rowMsg }}" target="_blank" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-300 font-bold text-[11px] transition" title="Tuma ujumbe WhatsApp">
                                                <i class="fa-brands fa-whatsapp text-emerald-600 mr-1 text-sm"></i> WhatsApp
                                            </a>
                                        @endif

                                        <!-- Reject Form -->
                                        <form action="{{ route('admin.students.reject', $st->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Je, una uhakika unataka kukataa ombi la mwanafunzi huyu? Akaunti yake itafutwa.')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold text-[11px] transition">
                                                <i class="fa-solid fa-xmark mr-1"></i> Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $pendingStudents->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.cbe>