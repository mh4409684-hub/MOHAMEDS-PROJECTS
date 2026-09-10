<x-layouts.cbe title="System Reports - CBE Portal">
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <h1 class="text-2xl font-black text-slate-900">Reports & Compliance</h1>
            <p class="text-xs text-slate-500 mt-1">Export attendance logs, student performance, and field evaluation summaries.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-900">GPS Field Attendance Audit</h3>
                    <p class="text-xs text-slate-500 mt-1">Detailed coordinate logs and distance compliance per student placement.</p>
                </div>
                <button type="button" class="w-full py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition">
                    Generate Log Report
                </button>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-900">E-Logbook Completion</h3>
                    <p class="text-xs text-slate-500 mt-1">Status of weekly reports and daily submissions reviewed by supervisors.</p>
                </div>
                <button type="button" class="w-full py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition">
                    Generate Logbook Report
                </button>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="h-10 w-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-900">Class Lecture Attendance</h3>
                    <p class="text-xs text-slate-500 mt-1">Dynamic code lecture session attendance rates by course and section.</p>
                </div>
                <button type="button" class="w-full py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition">
                    Generate Class Report
                </button>
            </div>
        </div>
    </div>
</x-layouts.cbe>
