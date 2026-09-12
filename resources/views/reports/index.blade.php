<x-layouts.cbe title="Kituo cha Ripoti (Reports Hub) - CBE Portal">
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-semibold text-slate-400">Ripoti za Mfumo & Takwimu</span>
                <h1 class="text-2xl font-black text-slate-900 mt-0.5">Kituo cha Uzalishaji wa Ripoti (Reports Hub)</h1>
                <p class="text-xs text-slate-500 mt-1">Tengeneza na pakua ripoti mbalimbali za wanafunzi, mafunzo ya vitendo (field), mahudhurio na takwimu za mfumo.</p>
            </div>
            <a href="{{ route('reports.system-statistics', ['format' => 'view']) }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition">
                <i class="fa-solid fa-chart-pie mr-1.5"></i> Takwimu za Mfumo
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- 1. Student Profile Report -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Ripoti ya Mwanafunzi (Student Profile Report)</h3>
                        <p class="text-xs text-slate-500">Taarifa zote za kimasomo, hadhi ya field, na muhtasari wa mwanafunzi.</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('reports.student-profile') }}" class="space-y-3 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Chagua Mwanafunzi</label>
                        <select name="student_id" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach(\App\Models\Student::with('user')->get() as $std)
                                <option value="{{ $std->id }}">{{ $std->user->name ?? 'Student' }} ({{ $std->user->registration_number ?? 'Reg' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Umbizo (Format)</label>
                        <select name="format" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="view">Onyesha Kwenye Tovuti (View Online)</option>
                            <option value="pdf">Pakua PDF</option>
                            <option value="excel">Pakua Excel</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition">
                        Tengeneza Ripoti ya Mwanafunzi &rarr;
                    </button>
                </form>
            </div>

            <!-- 2. Field Placement Report -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Ripoti ya Field Placement (Field Report)</h3>
                        <p class="text-xs text-slate-500">Ripoti kamili ya mafunzo kwa vitendo, masaa ya kazi, logbook na supervisor.</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('reports.field-placement') }}" class="space-y-3 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Chagua Field Placement</label>
                        <select name="placement_id" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            @foreach(\App\Models\FieldPlacement::with('student.user', 'hostOrganization')->get() as $fp)
                                <option value="{{ $fp->id }}">
                                    {{ $fp->student->user->name ?? 'Student' }} &mdash; {{ $fp->hostOrganization->name ?? 'Organization' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Umbizo (Format)</label>
                        <select name="format" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="view">Onyesha Kwenye Tovuti (View Online)</option>
                            <option value="pdf">Pakua PDF</option>
                            <option value="excel">Pakua Excel</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                        Tengeneza Ripoti ya Field &rarr;
                    </button>
                </form>
            </div>

            <!-- 3. Attendance Report -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-clipboard-user"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Ripoti ya Mahudhurio ya Darasani</h3>
                        <p class="text-xs text-slate-500">Takwimu na orodha ya mahudhurio ya somo na stream husika.</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('reports.attendance') }}" class="space-y-3 pt-2">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Somo (Course)</label>
                            <select name="course_id" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                                @foreach(\App\Models\Course::all() as $crs)
                                    <option value="{{ $crs->id }}">{{ $crs->code }} - {{ $crs->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Section</label>
                            <select name="section_id" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                                @foreach(\App\Models\Section::all() as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tarehe ya Kuanza</label>
                            <input type="date" name="start_date" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tarehe ya Mwisho</label>
                            <input type="date" name="end_date" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Umbizo (Format)</label>
                        <select name="format" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="view">Onyesha Kwenye Tovuti (View Online)</option>
                            <option value="pdf">Pakua PDF</option>
                            <option value="excel">Pakua Excel</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-sm transition">
                        Tengeneza Ripoti ya Mahudhurio &rarr;
                    </button>
                </form>
            </div>

            <!-- 4. System Statistics -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Takwimu za Mfumo Mzima (System Statistics)</h3>
                        <p class="text-xs text-slate-500">Muhtasari wa namba zote za wanafunzi, wakufunzi, logbooks, na placements.</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('reports.system-statistics') }}" class="space-y-3 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Umbizo (Format)</label>
                        <select name="format" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="view">Onyesha Kwenye Tovuti (View Online)</option>
                            <option value="pdf">Pakua PDF</option>
                            <option value="excel">Pakua Excel</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-sm transition">
                        Tazama Takwimu za Mfumo &rarr;
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.cbe>
