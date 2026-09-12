<x-layouts.cbe title="Sajili Mfanyakazi / Supervisor Mpya - CBE Portal">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <a href="{{ route('admin.staff.index') }}" class="text-xs font-bold text-blue-600 hover:underline inline-flex items-center gap-1 mb-1">
                    <i class="fa-solid fa-arrow-left"></i> Rudi Kwenye Orodha ya Staff
                </a>
                <h1 class="text-2xl font-black text-slate-900">Sajili Msimamizi / Mfanyakazi Mpya</h1>
                <p class="text-xs text-slate-500 mt-0.5">Sajili Field Supervisor, Mhadhiri (Lecturer), au Mratibu (Coordinator) wa Chuo.</p>
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
            <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Jina Kamili (Full Name) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Dr. Amani Juma" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Username ya Kuingilia <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="e.g. amani_supervisor" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Barua Pepe (Active Email) <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. amani@cbe.ac.tz au gmail.com" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nenosiri (Password - Isiyopungua herufi 8) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="password" value="{{ old('password', 'Supervisor@2025') }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Staff Type -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Aina ya Mfanyakazi (Staff Role) <span class="text-red-500">*</span>
                        </label>
                        <select name="staff_type" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white font-bold text-blue-900">
                            <option value="supervisor" {{ old('staff_type') == 'supervisor' ? 'selected' : '' }}>Field Supervisor (Msimamizi wa Field)</option>
                            <option value="lecturer" {{ old('staff_type') == 'lecturer' ? 'selected' : '' }}>Lecturer (Mhadhiri)</option>
                            <option value="coordinator" {{ old('staff_type') == 'coordinator' ? 'selected' : '' }}>Field Coordinator (Mratibu)</option>
                            <option value="admin" {{ old('staff_type') == 'admin' ? 'selected' : '' }}>Administrator (Msimamizi wa Mfumo)</option>
                        </select>
                    </div>

                    <!-- Campus -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Kampasi (Campus) <span class="text-red-500">*</span>
                        </label>
                        <select name="campus_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                            @foreach($campuses as $campus)
                                <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>
                                    {{ $campus->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Idara (Department)
                        </label>
                        <select name="department_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                            <option value="">-- Chagua Idara --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Designation -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Wadhifa (Designation / Title)
                        </label>
                        <input type="text" name="designation" value="{{ old('designation', 'Field Supervisor') }}" placeholder="e.g. Senior Field Supervisor" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Namba ya Simu (Phone)
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. +255 712 345 678" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.staff.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Ghairi (Cancel)
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-user-plus"></i> Sajili Mfanyakazi Sasa
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.cbe>
