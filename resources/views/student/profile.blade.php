<x-layouts.cbe title="Wasifu wa Mwanafunzi (Student Profile) - CBE">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white flex items-center justify-center font-black text-2xl shadow-md uppercase">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-900">{{ $user->name }}</h1>
                    <p class="text-xs text-slate-500 font-mono mt-0.5">
                        <span class="font-bold text-slate-700">Reg No:</span> {{ $user->registration_number ?? 'Haijawekwa' }} &bull; 
                        <span class="font-bold text-slate-700">Email:</span> {{ $user->email }}
                    </p>
                </div>
            </div>
            <a href="{{ route('student.dashboard') }}" class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                &larr; Rudi Dashibodi
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Academic Information (Read-only) -->
            <div class="md:col-span-1 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Taarifa za Kiakademia</h3>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[10px]">PROGRAMME</span>
                        <span class="font-bold text-slate-800">{{ $student->programme->name ?? 'N/A' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px]">CAMPUS</span>
                        <span class="font-bold text-slate-800">{{ $student->campus->name ?? 'Dar es Salaam' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px]">SECTION / STREAM</span>
                        <span class="font-bold text-slate-800">{{ $student->section->name ?? 'A' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px]">MWAKA WA MASOMO</span>
                        <span class="font-bold text-slate-800">Mwaka wa {{ $student->year_of_study ?? 1 }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px]">HALI YA USAJILI</span>
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                            {{ $student->enrollment_status ?? 'Enrolled' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Profile Edit Form -->
            <div class="md:col-span-2 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Hariri Wasifu (Edit Profile)</h3>

                <form method="POST" action="{{ route('student.update-profile') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jina Kamili (Full Name)</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Barua Pepe (Email)</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed">
                        <span class="text-[10px] text-slate-400">Barua pepe haiwezi kubadilishwa bila idhini ya Msimamizi Mkuu.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nambari ya Simu (Phone Number)</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="e.g. +255 712 345 678" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow transition">
                            <i class="fa-solid fa-save mr-1.5"></i> Hifadhi Mabadiliko
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.cbe>
