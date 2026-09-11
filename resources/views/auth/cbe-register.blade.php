<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - College of Business Education (CBE)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
    </style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4 selection:bg-blue-600 selection:text-white">
    <div class="w-full max-w-2xl my-8">
        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 shadow-xl shadow-blue-500/20 text-white text-2xl font-black mb-3">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">COLLEGE OF BUSINESS EDUCATION</h1>
            <p class="text-xs text-blue-400 font-semibold tracking-wide uppercase mt-1">Student Account Self-Registration Portal</p>
        </div>

        <!-- Registration Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100 p-8 sm:p-10">
            <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Student Registration Form</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Please provide accurate university credentials. Your account will be reviewed by the CBE Administrator before activation.</p>
                </div>
                <a href="{{ route('cbe.login') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                    &larr; Back to Login
                </a>
            </div>

            <!-- Error Alerts -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs">
                    <p class="font-bold mb-2 flex items-center gap-1.5 text-red-800 text-sm">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Kuna hitilafu katika taarifa za usajili:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 ml-2 text-xs">
                        @foreach ($errors->all() as $error)
                            @if(str_contains(strtolower($error), 'already been taken'))
                                <li class="text-red-800 font-semibold list-none -ml-2 p-3 bg-red-100/70 border border-red-200 rounded-xl my-2">
                                    <div class="flex items-start gap-2">
                                        <i class="fa-solid fa-circle-check text-emerald-600 text-base mt-0.5"></i>
                                        <div>
                                            <strong class="text-slate-900 block text-sm">Akaunti Hii Tayari Imeshasajiliwa Mfomoni!</strong>
                                            <span class="text-slate-600 text-xs mt-0.5 block">Huna haja ya kujisajili upya. Unaweza kuingia moja kwa moja kwenye mfumo sasa hivi.</span>
                                            <div class="mt-2.5">
                                                <a href="{{ route('cbe.login') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                                                    <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Bofya Hapa Kuingia (Login Portal) &rarr;
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @else
                                <li>{{ $error }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cbe.register.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Full Name -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="name">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="e.g. MOHAMEDY HAMADI"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                        >
                    </div>

                    <!-- Registration Number -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="registration_number">
                            Registration Number <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="registration_number"
                            name="registration_number"
                            value="{{ old('registration_number') }}"
                            placeholder="e.g. 03.5845.01.02.2025"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none transition font-mono"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Email Address -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="email">
                            Active Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="e.g. student@gmail.com"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                        >
                        <span class="text-[10px] text-slate-400">Confirmation email will be delivered to this address.</span>
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="username">
                            Portal Username <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="e.g. mohamedy_student"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Phone Number -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="phone">
                            Phone Number
                        </label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="e.g. +255 712 345 678"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                        >
                    </div>

                    <!-- Year of Study -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="year_of_study">
                            Year of Study <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="year_of_study"
                            name="year_of_study"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                        >
                            <option value="1" {{ old('year_of_study') == '1' ? 'selected' : '' }}>Year 1</option>
                            <option value="2" {{ old('year_of_study') == '2' ? 'selected' : '' }}>Year 2</option>
                            <option value="3" {{ old('year_of_study') == '3' ? 'selected' : '' }}>Year 3</option>
                            <option value="4" {{ old('year_of_study') == '4' ? 'selected' : '' }}>Year 4</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Campus -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="campus_id">
                            Campus <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="campus_id"
                            name="campus_id"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                        >
                            <option value="">-- Select Campus --</option>
                            @foreach($campuses as $campus)
                                <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>
                                    {{ $campus->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Programme -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="programme_id">
                            Degree / Diploma Programme <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="programme_id"
                            name="programme_id"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                        >
                            <option value="">-- Select Programme --</option>
                            @foreach($programmes as $prog)
                                <option value="{{ $prog->id }}" {{ old('programme_id') == $prog->id ? 'selected' : '' }}>
                                    {{ $prog->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="password">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="At least 6 characters"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                        >
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="password_confirmation">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Re-type password"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                        >
                    </div>
                </div>

                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 text-[11px] flex items-start gap-2 mt-2">
                    <i class="fa-solid fa-shield-halved text-amber-600 text-sm mt-0.5"></i>
                    <div>
                        <strong>Taarifa Muhimu:</strong> Wanafunzi pekee ndio wanaojaza fomu hii. Supervisors na Staff wengine husajiliwa moja kwa moja na Administrator. Baada ya kujisajili, ombi lako litathibitishwa na Admin na utapokea ujumbe kwenye email yako.
                    </div>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full mt-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3.5 rounded-xl transition duration-200 shadow-md hover:shadow-lg text-xs tracking-wider uppercase flex items-center justify-center gap-2"
                >
                    <i class="fa-solid fa-user-plus"></i> Submit Registration for Admin Approval
                </button>
            </form>

            <div class="mt-6 text-center text-xs text-slate-500">
                Already registered or approved?
                <a href="{{ route('cbe.login') }}" class="font-bold text-blue-600 hover:underline">
                    Sign In to Portal
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-slate-500 text-xs">
            <p>College of Business Education (CBE) &bull; Integrated E-Logbook & Attendance System</p>
        </div>
    </div>
</body>
</html>