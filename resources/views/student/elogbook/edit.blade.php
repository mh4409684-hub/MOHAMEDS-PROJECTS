<x-layouts.cbe title="Edit Logbook Entry - CBE Portal">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <h1 class="text-xl font-black text-slate-900">Edit Logbook Entry</h1>
                <p class="text-xs text-slate-500 mt-0.5">Update draft entry details.</p>
            </div>
            <a href="{{ route('student.elogbook.index') }}" class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                Back to List
            </a>
        </div>

        <form method="POST" action="{{ route('student.elogbook.update', $entry->id) }}" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Date of Activity *</label>
                    <input type="date" name="activity_date" value="{{ old('activity_date', $entry->activity_date?->toDateString()) }}" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Hours Worked *</label>
                    <input type="number" name="hours_worked" min="1" max="24" value="{{ old('hours_worked', $entry->hours_worked) }}" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Description of Main Duties & Tasks *</label>
                <textarea name="activity_description" rows="4" required class="w-full px-4 py-3 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('activity_description', $entry->activity_description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Skills & Knowledge Acquired</label>
                <textarea name="skills_learned" rows="2" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('skills_learned', $entry->skills_learned) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Challenges Encountered</label>
                    <textarea name="challenges" rows="2" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('challenges', $entry->challenges) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Solutions & Action Taken</label>
                    <textarea name="solutions" rows="2" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('solutions', $entry->solutions) }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('student.elogbook.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-md transition">
                    Update Entry
                </button>
            </div>
        </form>
    </div>
</x-layouts.cbe>
