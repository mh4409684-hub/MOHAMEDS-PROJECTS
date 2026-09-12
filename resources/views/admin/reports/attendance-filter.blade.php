<x-layouts.cbe title="Ripoti ya Mahudhurio - CBE Portal">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <a href="{{ route('admin.reports') }}" class="text-xs font-bold text-blue-600 hover:underline inline-flex items-center gap-1 mb-1">
                    <i class="fa-solid fa-arrow-left"></i> Rudi Kwenye Ripoti Zote
                </a>
                <h1 class="text-2xl font-black text-slate-900">Ripoti ya Mahudhurio ya Darasani</h1>
                <p class="text-xs text-slate-500 mt-0.5">Chagua kozi (Course) na darasa (Section) ili kuona ripoti kamili ya mahudhurio.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
            <form action="{{ route('admin.attendance-reports') }}" method="GET" class="space-y-6">
                <input type="hidden" name="generate" value="1">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Somo / Kozi (Course) <span class="text-red-500">*</span>
                        </label>
                        <select name="course_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                            <option value="">-- Chagua Kozi --</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Darasa / Section <span class="text-red-500">*</span>
                        </label>
                        <select name="section_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                            <option value="">-- Chagua Section --</option>
                            @foreach($sections as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-file-waveform"></i> Tengeneza Ripoti Sasa
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.cbe>
