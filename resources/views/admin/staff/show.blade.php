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

        @if(session('new_staff_credentials'))
            @php
                $cred = session('new_staff_credentials');
                $waPhone = preg_replace('/[^0-9]/', '', $cred['phone'] ?? '');
                if (str_starts_with($waPhone, '0')) {
                    $waPhone = '255' . substr($waPhone, 1);
                }
                $waText = urlencode("🎓 *COLLEGE OF BUSINESS EDUCATION (CBE)*\n"
                    . "Habari *{$cred['name']}*,\n\n"
                    . "Akaunti yako ya *{$cred['role']}* kwenye mfumo wa CBE imefunguliwa.\n\n"
                    . "📋 *Taarifa za Kuingilia:*\n"
                    . "🔗 Tovuti: " . url('/cbe/login') . "\n"
                    . "📧 Username: *{$cred['username']}*\n"
                    . "🔑 Nenosiri: *{$cred['password']}*\n\n"
                    . "Tafadhali ingia kwenye mfumo na ubadilishe nenosiri lako.");
                $waLink = !empty($waPhone) ? "https://wa.me/{$waPhone}?text={$waText}" : "https://wa.me/?text={$waText}";
            @endphp
            <div class="bg-emerald-900 text-white rounded-3xl p-6 shadow-xl border border-emerald-700 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-700/80 flex items-center justify-center text-xl text-emerald-200">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-base text-white">Akaunti Mpya ya Mfanyakazi Imekamilika</h3>
                            <p class="text-xs text-emerald-300">Taarifa hizi za siri zinaonekana sasa hivi pekee. Unaweza kuzinakili au kumtumia moja kwa moja kupitia WhatsApp.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 bg-emerald-950/60 p-4 rounded-2xl border border-emerald-800 text-xs">
                    <div>
                        <span class="text-emerald-400 block font-medium">Username:</span>
                        <span class="text-white font-mono font-bold text-sm">{{ $cred['username'] }}</span>
                    </div>
                    <div>
                        <span class="text-emerald-400 block font-medium">Email:</span>
                        <span class="text-white font-mono font-bold text-sm">{{ $cred['email'] }}</span>
                    </div>
                    <div>
                        <span class="text-emerald-400 block font-medium">Temporary Password:</span>
                        <span class="text-amber-300 font-mono font-black text-base">{{ $cred['password'] }}</span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-1">
                    <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs transition shadow-lg">
                        <i class="fa-brands fa-whatsapp text-base"></i> Tuma Taarifa Hizi Moja kwa Moja WhatsApp
                    </a>
                    <button onclick="navigator.clipboard.writeText('Username: {{ $cred['username'] }}\nPassword: {{ $cred['password'] }}\nLogin: {{ url('/cbe/login') }}'); alert('Taarifa zimenakiliwa!');" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition border border-white/20">
                        <i class="fa-solid fa-copy"></i> Nakili Taarifa
                    </button>
                </div>
            </div>
        @endif

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
