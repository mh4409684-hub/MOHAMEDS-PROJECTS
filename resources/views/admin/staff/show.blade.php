<x-layouts.cbe title="Taarifa za Mfanyakazi - CBE Portal">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <a href="{{ route('admin.staff.index') }}" class="text-xs font-bold text-blue-600 hover:underline inline-flex items-center gap-1 mb-1">
                    <i class="fa-solid fa-arrow-left"></i> Rudi Kwenye Orodha ya Staff
                </a>
                <h1 class="text-2xl font-black text-slate-900">{{ $staff->user->name ?? 'Staff Member' }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ $staff->designation ?? 'Staff' }} &bull; {{ $staff->campus->name ?? '' }}</p>
            </div>
            <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 uppercase">
                {{ $staff->employment_status ?? 'Active' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Profile Details -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Taarifa za Kibinafsi</h3>
                <div class="text-xs space-y-2.5 text-slate-600">
                    <div>
                        <span class="text-slate-400 block font-medium">Jina Kamili:</span>
                        <strong class="text-slate-900 text-sm">{{ $staff->user->name ?? '-' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Email Address:</span>
                        <strong class="text-slate-900">{{ $staff->user->email ?? '-' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Username:</span>
                        <strong class="text-slate-900 font-mono">{{ $staff->user->username ?? '-' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Namba ya Simu:</span>
                        <strong class="text-slate-900">{{ $staff->user->phone ?? 'Hajajaza' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Employment Details -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Taarifa za Ajira & Kazi</h3>
                <div class="text-xs space-y-2.5 text-slate-600">
                    <div>
                        <span class="text-slate-400 block font-medium">Aina ya Mfanyakazi (Role):</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-800 uppercase">
                            {{ $staff->staff_type ?? 'Staff' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Wadhifa (Designation):</span>
                        <strong class="text-slate-900">{{ $staff->designation ?? 'Field Supervisor' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Kampasi (Campus):</span>
                        <strong class="text-slate-900">{{ $staff->campus->name ?? '-' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Idara (Department):</span>
                        <strong class="text-slate-900">{{ $staff->department->name ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.cbe>
