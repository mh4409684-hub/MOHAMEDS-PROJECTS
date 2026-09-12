<x-layouts.cbe title="Hariri Mwanafunzi - CBE Portal">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <a href="{{ route('admin.students.show', $student->id) }}" class="text-xs font-bold text-blue-600 hover:underline inline-flex items-center gap-1 mb-1">
                    <i class="fa-solid fa-arrow-left"></i> Rudi Kwenye Maelezo ya Mwanafunzi
                </a>
                <h1 class="text-2xl font-black text-slate-900">Hariri Taarifa za Mwanafunzi</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ $student->user->name }} &bull; Reg No: {{ $student->user->registration_number }}</p>
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
            <form action="{{ route('admin.students.update', $student->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Jina Kamili (Full Name) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $student->user->name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Namba ya Simu (Phone Number)
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $student->user->phone) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Year of Study -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Mwaka wa Masomo (Year of Study) <span class="text-red-500">*</span>
                        </label>
                        <select name="year_of_study" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                            @for($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}" {{ old('year_of_study', $student->year_of_study) == $i ? 'selected' : '' }}>Year {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Section -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Darasa / Section <span class="text-red-500">*</span>
                        </label>
                        <select name="section_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                            @foreach($sections as $sec)
                                <option value="{{ $sec->id }}" {{ old('section_id', $student->section_id) == $sec->id ? 'selected' : '' }}>
                                    {{ $sec->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Enrollment Status -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Hali ya Mwanafunzi (Status) <span class="text-red-500">*</span>
                        </label>
                        <select name="enrollment_status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                            <option value="active" {{ old('enrollment_status', $student->enrollment_status) === 'active' ? 'selected' : '' }}>Active (Amesajiliwa)</option>
                            <option value="suspended" {{ old('enrollment_status', $student->enrollment_status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="graduated" {{ old('enrollment_status', $student->enrollment_status) === 'graduated' ? 'selected' : '' }}>Graduated</option>
                            <option value="withdrawn" {{ old('enrollment_status', $student->enrollment_status) === 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                        </select>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Maelezo ya Ziada (Notes)
                    </label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('notes', $student->notes) }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.students.show', $student->id) }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Ghairi (Cancel)
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> Hifadhi Mabadiliko
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.cbe>
