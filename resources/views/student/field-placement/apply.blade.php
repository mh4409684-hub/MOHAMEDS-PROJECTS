<x-layouts.cbe title="Omba / Jaza Eneo la Field - CBE Portal">
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl">
            <a href="{{ route('student.dashboard') }}" class="text-xs font-bold text-blue-300 hover:text-white inline-flex items-center gap-1 mb-2 transition">
                <i class="fa-solid fa-arrow-left"></i> Rudi Kwenye Dashboard
            </a>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                Usajili wa Eneo Lako la Field
            </h1>
            <p class="text-blue-100/80 text-xs sm:text-sm mt-1">
                Kama umepata eneo la mafunzo ya vitendo (Field Placement), jaza taarifa za taasisi na msimamizi wako wa huko hapa chini.
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 text-xs text-rose-800 flex items-start gap-3">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-base mt-0.5 shrink-0"></i>
                <div>
                    <h4 class="font-bold text-rose-900 mb-1">Tafadhali rekebisha hitilafu zifuatazo:</h4>
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('student.field-placement.store-apply') }}" method="POST" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
            @csrf

            <!-- Taarifa za Taasisi / Kampuni -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2 border-b border-slate-100 pb-2">
                    <i class="fa-solid fa-building text-blue-600"></i> Taarifa za Shirika / Taasisi ya Field
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jina Kamili la Shirika / Kampuni / Ofisi <span class="text-rose-500">*</span></label>
                        <input type="text" name="organization_name" value="{{ old('organization_name', $existingPlacement?->hostOrganization?->name) }}" required placeholder="mfano: Benki Kuu ya Tanzania (BOT), TRA, TBL, N.K." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Sekta / Industry</label>
                        <input type="text" name="industry" value="{{ old('industry', $existingPlacement?->hostOrganization?->industry) }}" placeholder="mfano: Banking, ICT, Logistics, Gov" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Mji / Wilaya <span class="text-rose-500">*</span></label>
                        <input type="text" name="city" value="{{ old('city', $existingPlacement?->hostOrganization?->city) }}" required placeholder="mfano: Dar es Salaam, Dodoma, Arusha" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Anwani Kamili ya Eneo la Kazi (Street / Building) <span class="text-rose-500">*</span></label>
                        <input type="text" name="address" value="{{ old('address', $existingPlacement?->hostOrganization?->address) }}" required placeholder="mfano: Samora Avenue, Mtaa wa Posta, Jengo la PSPF Gorofa ya 4" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Simu ya Ofisi / Taasisi</label>
                        <input type="text" name="organization_phone" value="{{ old('organization_phone', $existingPlacement?->hostOrganization?->phone) }}" placeholder="mfano: 022 211 XXXX" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email ya Ofisi</label>
                        <input type="email" name="organization_email" value="{{ old('organization_email', $existingPlacement?->hostOrganization?->email) }}" placeholder="mfano: info@company.co.tz" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Taarifa za Supervisor wa Field (Kazini) -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2 border-b border-slate-100 pb-2">
                    <i class="fa-solid fa-user-tie text-emerald-600"></i> Msimamizi Wako Kwenye Eneo la Kazi (Host Supervisor)
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jina Kamili la Msimamizi <span class="text-rose-500">*</span></label>
                        <input type="text" name="supervisor_name" value="{{ old('supervisor_name', $existingPlacement?->hostOrganization?->contact_person) }}" required placeholder="mfano: Eng. Juma Ally / Bi. Grace Mwangi" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Namba ya Simu ya Msimamizi <span class="text-rose-500">*</span></label>
                        <input type="text" name="supervisor_phone" value="{{ old('supervisor_phone', $existingPlacement?->hostOrganization?->phone) }}" required placeholder="mfano: 0754 123 456" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Wadhifa wa Msimamizi Kazini</label>
                        <input type="text" name="supervisor_title" value="{{ old('supervisor_title', $existingPlacement?->hostOrganization?->contact_title) }}" placeholder="mfano: Head of IT / Senior Accountant / Branch Manager" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Muda wa Mafunzo -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2 border-b border-slate-100 pb-2">
                    <i class="fa-solid fa-calendar-days text-indigo-600"></i> Muda wa Mafunzo ya Vitendo
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tarehe ya Kuanza <span class="text-rose-500">*</span></label>
                        <input type="date" name="start_date" value="{{ old('start_date', $existingPlacement?->start_date?->format('Y-m-d') ?: date('Y-m-d')) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tarehe ya Kumaliza <span class="text-rose-500">*</span></label>
                        <input type="date" name="end_date" value="{{ old('end_date', $existingPlacement?->end_date?->format('Y-m-d') ?: date('Y-m-d', strtotime('+60 days'))) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jumla ya Siku za Field <span class="text-rose-500">*</span></label>
                        <input type="number" name="total_days" value="{{ old('total_days', $existingPlacement?->total_days ?: 60) }}" min="10" max="180" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('student.dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition">
                    Ghairi
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i> Hifadhi Taarifa za Field
                </button>
            </div>
        </form>
    </div>
</x-layouts.cbe>
