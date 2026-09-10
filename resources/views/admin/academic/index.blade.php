<x-layouts.cbe title="Academic & System Settings - CBE Portal">
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <h1 class="text-2xl font-black text-slate-900">Academic & System Configuration</h1>
            <p class="text-xs text-slate-500 mt-1">Manage Campuses, Departments, Programmes, Academic Years, and Courses.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-sm text-slate-900 mb-3 flex items-center"><i class="fa-solid fa-building-columns mr-2 text-blue-600"></i> Campuses ({{ $campuses->count() }})</h3>
                <ul class="text-xs divide-y divide-slate-100">
                    @foreach($campuses as $c)
                        <li class="py-2 flex justify-between font-medium"><span>{{ $c->name }}</span> <span class="text-slate-400 font-mono">{{ $c->code }}</span></li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-sm text-slate-900 mb-3 flex items-center"><i class="fa-solid fa-graduation-cap mr-2 text-indigo-600"></i> Programmes ({{ $programmes->count() }})</h3>
                <ul class="text-xs divide-y divide-slate-100">
                    @foreach($programmes as $p)
                        <li class="py-2 flex justify-between font-medium"><span>{{ $p->name }}</span> <span class="text-slate-400 font-mono">{{ $p->code }}</span></li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-sm text-slate-900 mb-3 flex items-center"><i class="fa-solid fa-calendar mr-2 text-emerald-600"></i> Academic Years ({{ $academicYears->count() }})</h3>
                <ul class="text-xs divide-y divide-slate-100">
                    @foreach($academicYears as $ay)
                        <li class="py-2 flex justify-between font-medium"><span>{{ $ay->name }}</span> <span class="text-emerald-600 font-bold">Current</span></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-layouts.cbe>
