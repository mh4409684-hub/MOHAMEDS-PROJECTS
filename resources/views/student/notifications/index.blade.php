<x-layouts.cbe title="Taarifa (Notifications) - CBE Student">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Taarifa Zako (Notifications)</h1>
                <p class="text-xs text-slate-500 mt-1">Taarifa za ukaguzi wa logbook, maoni ya supervisor, na matangazo ya chuo.</p>
            </div>
            <a href="{{ route('student.dashboard') }}" class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                &larr; Dashibodi
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            @if($notifications->isEmpty())
                <div class="py-14 text-center">
                    <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl">
                        <i class="fa-regular fa-bell"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Hakuna Taarifa Zozote</h3>
                    <p class="text-xs text-slate-500 mt-1">Huna taarifa mpya kwa sasa.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($notifications as $notif)
                        <div class="py-4 flex items-start justify-between gap-4 {{ $notif->is_read ? 'opacity-70' : '' }}">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl {{ $notif->is_read ? 'bg-slate-100 text-slate-400' : 'bg-blue-50 text-blue-600' }} flex items-center justify-center shrink-0 text-base">
                                    <i class="fa-solid fa-bell"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">{{ $notif->title }}</h4>
                                    <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ $notif->message }}</p>
                                    <span class="text-[10px] text-slate-400 font-mono mt-1 inline-block">
                                        {{ $notif->created_at ? $notif->created_at->diffForHumans() : '' }}
                                    </span>
                                </div>
                            </div>
                            @if(!$notif->is_read)
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600 shrink-0 mt-2"></span>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.cbe>
