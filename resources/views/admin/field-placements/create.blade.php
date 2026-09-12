<x-layouts.cbe title="Panga Mwanafunzi Kwenye Field - CBE Portal">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <a href="{{ route('admin.field-placements') }}" class="text-xs font-bold text-blue-600 hover:underline inline-flex items-center gap-1 mb-1">
                    <i class="fa-solid fa-arrow-left"></i> Rudi Kwenye Field Placements
                </a>
                <h1 class="text-2xl font-black text-slate-900">Panga Mwanafunzi Kwenye Field (New Placement)</h1>
                <p class="text-xs text-slate-500 mt-0.5">Chagua mwanafunzi (mfano: Zulfa), eneo la mafunzo (Host Organization), na umkabidhi Msimamizi (Supervisor).</p>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs">
                <p class="font-bold mb-1">Tafadhali rekebisha hitilafu zifuatazo:</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
            <form action="{{ route('admin.field-placements.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Student Selection -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        1. Chagua Mwanafunzi <span class="text-red-500">*</span>
                    </label>
                    <select name="student_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                        <option value="">-- Chagua Mwanafunzi --</option>
                        @foreach($students as $st)
                            <option value="{{ $st->id }}" {{ old('student_id') == $st->id ? 'selected' : '' }}>
                                {{ $st->user->name }} (Reg: {{ $st->user->registration_number }}) - {{ $st->programme->name ?? 'Course' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Host Organization -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        2. Shirika / Kampuni ya Mafunzo (Host Organization) <span class="text-red-500">*</span>
                    </label>
                    <select name="host_organization_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                        <option value="">-- Chagua Eneo la Mafunzo --</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org->id }}" {{ old('host_organization_id') == $org->id ? 'selected' : '' }}>
                                {{ $org->name }} ({{ $org->city }}, {{ $org->region }}) &bull; GPS Geofence: {{ $org->geofence_radius_meters ?? 300 }}m
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Supervisor Assignment (Optional but Recommended) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        3. Mkabidhi Msimamizi (Assign Field Supervisor)
                    </label>
                    <select name="supervisor_staff_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                        <option value="">-- Chagua Msimamizi (Unaweza kuweka sasa au baadae) --</option>
                        @foreach($supervisors as $sup)
                            <option value="{{ $sup->id }}" {{ old('supervisor_staff_id') == $sup->id ? 'selected' : '' }}>
                                {{ $sup->user->name }} ({{ $sup->designation ?? 'Supervisor' }}) - {{ $sup->campus->name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">Mwanafunzi huyu ataonekana moja kwa moja kwenye akaunti ya Msimamizi huyu atakapoingia.</p>
                </div>

                <!-- Dates & Days -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Tarehe ya Kuanza <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Tarehe ya Kumaliza <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d', strtotime('+60 days'))) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Jumla ya Siku za Field <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="total_days" value="{{ old('total_days', 60) }}" min="1" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.field-placements') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Ghairi (Cancel)
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-check"></i> Kamilisha & Panga Sasa
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.cbe>
