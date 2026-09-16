<x-layouts.cbe title="Owner System Controls - CBE Portal">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 rounded-3xl border border-indigo-900/50 shadow-xl text-white relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] font-black uppercase tracking-wider">
                        👑 System Creator & Super Admin
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full bg-blue-500/20 text-blue-300 border border-blue-500/30 text-[10px] font-black uppercase tracking-wider">
                        Proprietary License
                    </span>
                </div>
                <h1 class="text-2xl font-black tracking-tight">Umiliki na Udhibiti Mkuu wa Mfumo (Owner Console)</h1>
                <p class="text-xs text-slate-300 mt-1">Ukurasa huu unalinda umiliki wako halali. Una uwezo kamili wa kuzima au kuwasha mfumo wakati wowote.</p>
            </div>
            <div class="relative z-10 flex items-center gap-2">
                <div class="px-4 py-2 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 text-right">
                    <div class="text-[10px] text-slate-300">Mmiliki wa Mfumo:</div>
                    <div class="text-xs font-black text-amber-300">{{ $control->owner_name }}</div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Kill Switch & Controls -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Kill Switch Card -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
                    <div class="flex items-center justify-between pb-6 border-b border-slate-100 mb-6">
                        <div>
                            <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-power-off text-rose-600"></i>
                                Server Kill-Switch (Kuzima / Kuwasha Mfumo)
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">
                                Ukizima hapa, hakuna mwanafunzi wala supervisor anayeweza kuingia wala kuona taarifa yoyote. Mfumo utawaletea kioo cha usalama (Lockout Screen).
                            </p>
                        </div>
                        <div>
                            @if($control->is_system_locked)
                                <span class="px-3 py-1.5 rounded-full bg-rose-50 border border-rose-200 text-rose-700 font-black text-xs uppercase flex items-center gap-1.5 shadow-sm">
                                    <span class="w-2 h-2 rounded-full bg-rose-600 animate-ping"></span>
                                    LOCKED (Umezimwa)
                                </span>
                            @else
                                <span class="px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-black text-xs uppercase flex items-center gap-1.5 shadow-sm">
                                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                    ACTIVE (Uko Hewani)
                                </span>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('admin.owner-control.update') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="action" value="toggle_lock">

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Ujumbe wa Usalama Utakaoonekana Mfumo Ukizimwa:
                            </label>
                            <textarea
                                name="lock_reason"
                                rows="3"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-xs text-slate-800 focus:ring-2 focus:ring-rose-500 focus:outline-none transition"
                                placeholder="Andika sababu ya kuzima mfumo..."
                            >{{ $control->lock_reason }}</textarea>
                        </div>

                        <div class="pt-2 flex items-center justify-between">
                            <span class="text-xs text-slate-500">
                                <i class="fa-solid fa-shield-halved text-blue-600 mr-1"></i> Ni wewe pekee (<code class="font-bold text-blue-700">mh4409684@gmail.com</code>) mwenye ufunguo wa hii switch.
                            </span>

                            @if($control->is_system_locked)
                                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-lg shadow-emerald-600/30 transition">
                                    <i class="fa-solid fa-play"></i> Washa Mfumo Tena (Unlock System)
                                </button>
                            @else
                                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-lg shadow-rose-600/30 transition" onclick="return confirm('Je, una uhakika unataka KUZIMA mfumo mzima? Hakuna mtumiaji yeyote atakayeweza kuingia mpaka utakapoamua kuwasha tena.')">
                                    <i class="fa-solid fa-power-off"></i> Zima Mfumo Sasa (Emergency Lockout)
                                </button>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Broadcast Announcement Card -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
                    <h2 class="text-base font-black text-slate-900 mb-1 flex items-center gap-2">
                        <i class="fa-solid fa-bullhorn text-indigo-600"></i>
                        Tangazo Maalum la Mfumo (Global Announcement Banner)
                    </h2>
                    <p class="text-xs text-slate-500 mb-4">Ujumbe huu utaonekana juu kabisa ya dashibodi ya kila mwanafunzi na supervisor.</p>

                    <form action="{{ route('admin.owner-control.update') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="action" value="save_announcement">

                        <textarea
                            name="system_announcement"
                            rows="2"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-xs text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
                            placeholder="Weka tangazo hapa (mfano: Tafadhali hakikisheni mnasaini mahudhurio kabla ya saa 10 jioni)..."
                        >{{ $control->system_announcement }}</textarea>

                        <div class="flex justify-end">
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow transition">
                                Hifadhi Tangazo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right 1 Col: Ownership Certificate & Render Details -->
            <div class="space-y-6">
                <!-- Ownership Certificate Card -->
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-3xl border border-amber-200/80 p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center text-sm shadow">
                            <i class="fa-solid fa-certificate"></i>
                        </div>
                        <h3 class="text-sm font-black text-amber-950">Cheti cha Umiliki (IP Rights)</h3>
                    </div>

                    <div class="space-y-3 text-xs text-amber-950/80">
                        <div class="pb-2 border-b border-amber-200/60">
                            <span class="block text-[10px] text-amber-800 uppercase font-bold">Mvumbuzi na Muundaji Mkuu:</span>
                            <span class="font-black text-slate-900 text-sm">{{ $control->owner_name }}</span>
                        </div>
                        <div class="pb-2 border-b border-amber-200/60">
                            <span class="block text-[10px] text-amber-800 uppercase font-bold">Akaunti ya Umiliki:</span>
                            <span class="font-mono font-bold text-blue-900">{{ $control->owner_email }}</span>
                        </div>
                        <div class="pb-2 border-b border-amber-200/60">
                            <span class="block text-[10px] text-amber-800 uppercase font-bold">Nambari ya Leseni (License Key):</span>
                            <span class="font-mono font-black text-emerald-800">{{ $control->system_license_key }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-amber-800 uppercase font-bold">Haki Miliki:</span>
                            <span class="text-[11px] font-semibold">Hakimiliki zote zimehifadhiwa (All Rights Reserved) &copy; {{ date('Y') }}. Msimbo na muundo ni mali ya kipekee ya mmiliki.</span>
                        </div>
                    </div>
                </div>

                <!-- Render Cloud Integration Card -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center text-sm shadow">
                            <i class="fa-solid fa-cloud"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900">Render 24/7 Controls</h3>
                            <span class="text-[10px] text-slate-400">Dhibiti Server Moja kwa Moja</span>
                        </div>
                    </div>

                    <form action="{{ route('admin.owner-control.update') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="action" value="save_render">

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Render Service ID:
                            </label>
                            <input
                                type="text"
                                name="render_service_id"
                                value="{{ $control->render_service_id }}"
                                placeholder="srv-xxxxxxxxxx"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono"
                            >
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Render API Key:
                            </label>
                            <input
                                type="password"
                                name="render_api_key"
                                value="{{ $control->render_api_key }}"
                                placeholder="rnd_xxxxxxxxxxxxxxxx"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono"
                            >
                        </div>

                        <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition shadow">
                            Hifadhi Render Keys
                        </button>
                    </form>
                </div>

                <!-- Email Delivery HTTPS API (Resend) Card -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm shadow">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900">Email Delivery API (Resend HTTPS)</h3>
                            <span class="text-[10px] text-slate-400">Inatuma Barua Pepe Bila Kuzuiwa Render</span>
                        </div>
                    </div>

                    <form action="{{ route('admin.owner-control.update') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="action" value="save_email">

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Resend API Key:
                            </label>
                            <input
                                type="password"
                                name="resend_api_key"
                                value="{{ $control->resend_api_key }}"
                                placeholder="re_xxxxxxxxxxxxxxxxxxxx"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono"
                            >
                            <span class="text-[9px] text-slate-400">Pata bure kutoka <a href="https://resend.com" target="_blank" class="text-blue-600 underline">resend.com</a> (Inaruhusu barua pepe 3,000 bure/mwezi).</span>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                    Sender Email:
                                </label>
                                <input
                                    type="text"
                                    name="mail_from_address"
                                    value="{{ $control->mail_from_address ?: 'onboarding@resend.dev' }}"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs"
                                >
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                    Sender Name:
                                </label>
                                <input
                                    type="text"
                                    name="mail_from_name"
                                    value="{{ $control->mail_from_name ?: 'CBE Field Portal' }}"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs"
                                >
                            </div>
                        </div>

                        <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition shadow">
                            Hifadhi Mipangilio ya Email
                        </button>
                    </form>
                </div>

                <!-- WhatsApp Gateway API (UltraMsg / Green API) Card -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm shadow">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900">WhatsApp Gateway (UltraMsg)</h3>
                            <span class="text-[10px] text-slate-400">Tuma OTP & Welcome Messages Moja kwa Moja WhatsApp</span>
                        </div>
                    </div>

                    <form action="{{ route('admin.owner-control.update') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="action" value="save_whatsapp">

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                WhatsApp Instance ID:
                            </label>
                            <input
                                type="text"
                                name="whatsapp_instance_id"
                                value="{{ $control->whatsapp_instance_id }}"
                                placeholder="instance12345"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono"
                            >
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                WhatsApp Token:
                            </label>
                            <input
                                type="password"
                                name="whatsapp_token"
                                value="{{ $control->whatsapp_token }}"
                                placeholder="Token uliyopewa baada ya scan QR"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono"
                            >
                            <span class="text-[9px] text-slate-400">Pata bure kutoka <a href="https://ultramsg.com" target="_blank" class="text-emerald-600 underline">ultramsg.com</a> kwa kuunganisha WhatsApp yako mpya.</span>
                        </div>

                        <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow">
                            Hifadhi Mipangilio ya WhatsApp
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.cbe>