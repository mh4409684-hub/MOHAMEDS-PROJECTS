<x-layouts.cbe title="Register Student - CBE Portal">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <h1 class="text-xl font-black text-slate-900">Enrol New Student</h1>
                <p class="text-xs text-slate-500 mt-0.5">Create user account and assign academic programme.</p>
            </div>
            <a href="{{ route('admin.students.index') }}" class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                Back to List
            </a>
        </div>

        <form method="POST" action="{{ route('admin.students.store') }}" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Full Name *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Registration Number *</label>
                    <input type="text" name="registration_number" placeholder="e.g. CBE/BIT/2026/001" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address *</label>
                    <input type="email" name="email" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Phone Number</label>
                    <input type="text" name="phone" class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Campus *</label>
                    <select name="campus_id" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                        @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}">{{ $campus->name }} ({{ $campus->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Programme *</label>
                    <select name="programme_id" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                        @foreach($programmes as $prog)
                            <option value="{{ $prog->id }}">{{ $prog->name }} ({{ $prog->code }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.students.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-md transition">
                    Complete Registration
                </button>
            </div>
        </form>
    </div>
</x-layouts.cbe>
