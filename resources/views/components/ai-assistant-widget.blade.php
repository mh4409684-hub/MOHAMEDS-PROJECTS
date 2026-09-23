<!-- MohamedTech Pro AI Support & Guidance Assistant Widget -->
<div id="mohamedtech-ai-container" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-50 font-sans">
    <!-- Floating Launcher Button -->
    <div class="relative flex items-center justify-end">
        <!-- Notification tooltip (shows once or on hover) -->
        <div id="ai-launcher-tooltip" class="hidden sm:flex items-center gap-2 mr-3 px-3 py-1.5 bg-slate-900/95 text-white text-xs font-semibold rounded-full shadow-lg border border-slate-700 backdrop-blur-sm pointer-events-none transition-all duration-300">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Je, unahitaji msaada? Uliza AI</span>
        </div>

        <button
            type="button"
            id="ai-widget-toggle-btn"
            onclick="toggleAiChatWidget()"
            class="relative flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white shadow-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 border-2 border-white/30 focus:outline-none"
            aria-label="Open AI Assistant"
            title="Msaada wa AI na Mawasiliano"
        >
            <!-- Normal Icon -->
            <span id="ai-icon-open" class="flex items-center justify-center">
                <i class="fa-solid fa-robot text-2xl animate-bounce"></i>
            </span>
            <!-- Close Icon (Hidden by default) -->
            <span id="ai-icon-close" class="hidden flex items-center justify-center">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </span>

            <!-- Pulsing active status ring -->
            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-white"></span>
            </span>
        </button>
    </div>

    <!-- AI Chat Window -->
    <div
        id="ai-chat-window"
        class="hidden fixed inset-x-3 bottom-20 sm:inset-auto sm:right-6 sm:bottom-24 sm:w-[410px] sm:max-w-[calc(100vw-3rem)] h-[540px] max-h-[82vh] bg-slate-900/98 backdrop-blur-xl border border-slate-700/80 rounded-3xl shadow-2xl flex-col overflow-hidden transition-all duration-300 transform scale-95 opacity-0 z-50 text-slate-100"
    >
        <!-- Header -->
        <div class="px-5 py-3.5 bg-gradient-to-r from-blue-700 via-indigo-700 to-purple-700 border-b border-indigo-500/30 flex items-center justify-between shadow-md">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 text-white shadow-inner">
                        <i class="fa-solid fa-robot text-xl text-amber-300"></i>
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400 border-2 border-slate-900"></span>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <h3 class="text-sm font-bold text-white tracking-wide leading-tight">MohamedTech AI</h3>
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-400 text-slate-900 tracking-wider">PRO</span>
                    </div>
                    <p class="text-[11px] text-blue-100/90 leading-tight">Msaada & Mwongozo (24/7)</p>
                </div>
            </div>

            <div class="flex items-center gap-1">
                <a
                    href="https://wa.me/255777568288?text=Habari%20Eng.%20Mohamedy,%20nahitaji%20msaada%20kwenye%20mfumo%20wa%20CBE"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="p-2 rounded-xl text-emerald-300 hover:text-white hover:bg-emerald-600/30 transition text-sm"
                    title="Wasiliana WhatsApp ya Moja kwa Moja"
                >
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                </a>
                <button
                    type="button"
                    onclick="toggleAiChatWidget()"
                    class="p-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition text-sm"
                    aria-label="Funga AI Assistant"
                >
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Chat History Area -->
        <div id="ai-chat-messages" class="flex-1 p-4 overflow-y-auto space-y-3 scroll-smooth text-xs sm:text-[13px] bg-gradient-to-b from-slate-900/60 to-slate-950/80">
            <!-- Welcome message -->
            <div class="flex items-start gap-2.5">
                <div class="w-7 h-7 rounded-xl bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-robot text-xs"></i>
                </div>
                <div class="max-w-[85%] bg-slate-800/90 border border-slate-700/80 rounded-2xl rounded-tl-sm p-3.5 shadow-sm text-slate-200 leading-relaxed">
                    <p class="font-semibold text-white mb-1.5 flex items-center gap-1.5">
                        <span>👋 Habari! Karibu CBE Portal</span>
                    </p>
                    <p class="text-slate-300 text-xs leading-relaxed">
                        Mimi ni msaidizi wako wa akili bandia (AI). Unaweza kuniuliza chochote kuhusu usajili, nenosiri, GPS attendance, e-logbook, au kupata mawasiliano ya haraka ya <strong class="text-amber-400">Eng. Mohamedy Hamadi</strong>.
                    </p>
                </div>
            </div>

            <!-- Quick Action Chips -->
            <div class="pl-9 pr-2 pt-1 pb-1">
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-2">Chagua Msaada wa Haraka:</span>
                <div class="flex flex-wrap gap-1.5">
                    <button
                        type="button"
                        onclick="sendQuickAiQuery('Wasiliana Nasi')"
                        class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-indigo-600/30 border border-slate-700 hover:border-indigo-500/50 text-[11px] text-slate-200 hover:text-white transition flex items-center gap-1.5 active:scale-95"
                    >
                        <i class="fa-solid fa-phone text-emerald-400 text-[10px]"></i>
                        <span>📞 Wasiliana Nasi</span>
                    </button>
                    <button
                        type="button"
                        onclick="sendQuickAiQuery('Msaada wa Usajili wa Mwanafunzi')"
                        class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-indigo-600/30 border border-slate-700 hover:border-indigo-500/50 text-[11px] text-slate-200 hover:text-white transition flex items-center gap-1.5 active:scale-95"
                    >
                        <i class="fa-solid fa-user-plus text-blue-400 text-[10px]"></i>
                        <span>📝 Usajili Mpya</span>
                    </button>
                    <button
                        type="button"
                        onclick="sendQuickAiQuery('Nimesahau Password yangu')"
                        class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-indigo-600/30 border border-slate-700 hover:border-indigo-500/50 text-[11px] text-slate-200 hover:text-white transition flex items-center gap-1.5 active:scale-95"
                    >
                        <i class="fa-solid fa-key text-amber-400 text-[10px]"></i>
                        <span>🔐 Umesahau Password?</span>
                    </button>
                    <button
                        type="button"
                        onclick="sendQuickAiQuery('Jinsi ya kupiga mahudhurio ya GPS')"
                        class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-indigo-600/30 border border-slate-700 hover:border-indigo-500/50 text-[11px] text-slate-200 hover:text-white transition flex items-center gap-1.5 active:scale-95"
                    >
                        <i class="fa-solid fa-location-dot text-rose-400 text-[10px]"></i>
                        <span>📍 Mahudhurio ya GPS</span>
                    </button>
                    <button
                        type="button"
                        onclick="sendQuickAiQuery('Jinsi ya kujaza E-Logbook')"
                        class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-indigo-600/30 border border-slate-700 hover:border-indigo-500/50 text-[11px] text-slate-200 hover:text-white transition flex items-center gap-1.5 active:scale-95"
                    >
                        <i class="fa-solid fa-book-open text-purple-400 text-[10px]"></i>
                        <span>📖 Kujaza E-Logbook</span>
                    </button>
                </div>
            </div>

            <!-- Contact Card Banner -->
            <div class="mx-2 p-3 rounded-2xl bg-gradient-to-r from-emerald-950/60 to-slate-900 border border-emerald-500/30 flex items-center justify-between gap-2 shadow-sm">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                        <i class="fa-brands fa-whatsapp text-base"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[11px] font-bold text-white truncate">Eng. Mohamedy Hamadi</div>
                        <div class="text-[10px] text-emerald-400 truncate">+255 777 568 288</div>
                    </div>
                </div>
                <a
                    href="https://wa.me/255777568288?text=Habari%20Eng.%20Mohamedy,%20nahitaji%20msaada%20kwenye%20mfumo%20wa%20CBE"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[11px] rounded-xl transition shadow-sm shrink-0 flex items-center gap-1"
                >
                    <span>WhatsApp</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                </a>
            </div>
        </div>

        <!-- Typing Indicator (Hidden by default) -->
        <div id="ai-typing-indicator" class="hidden px-5 py-2 text-xs text-indigo-300 items-center gap-2 bg-slate-900/90 border-t border-slate-800">
            <i class="fa-solid fa-circle-notch fa-spin text-indigo-400"></i>
            <span>AI inafikiria jibu sahihi...</span>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-slate-950 border-t border-slate-800">
            <form id="ai-chat-form" onsubmit="handleAiFormSubmit(event)" class="flex items-center gap-2">
                <input
                    type="text"
                    id="ai-user-input"
                    placeholder="Andika swali au shida yako hapa..."
                    class="flex-1 px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-slate-100 placeholder-slate-500 text-xs sm:text-sm focus:outline-none transition"
                    autocomplete="off"
                />
                <button
                    type="submit"
                    id="ai-send-btn"
                    class="w-10 h-10 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-95 text-white flex items-center justify-center transition shadow-md shrink-0 focus:outline-none"
                    aria-label="Tuma Ujumbe"
                >
                    <i class="fa-solid fa-paper-plane text-xs sm:text-sm"></i>
                </button>
            </form>
            <div class="mt-1.5 flex items-center justify-between px-1 text-[10px] text-slate-500">
                <span class="flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>AI Online</span>
                </span>
                <span class="font-semibold text-slate-400">Powered by MohamedTech Pro</span>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleAiChatWidget() {
        const win = document.getElementById('ai-chat-window');
        const iconOpen = document.getElementById('ai-icon-open');
        const iconClose = document.getElementById('ai-icon-close');
        const tooltip = document.getElementById('ai-launcher-tooltip');

        if (!win) return;

        if (win.classList.contains('hidden')) {
            win.classList.remove('hidden');
            setTimeout(() => {
                win.classList.remove('scale-95', 'opacity-0');
                win.classList.add('scale-100', 'opacity-100');
            }, 10);
            if (iconOpen) iconOpen.classList.add('hidden');
            if (iconClose) iconClose.classList.remove('hidden');
            if (tooltip) tooltip.classList.add('hidden');
            setTimeout(() => {
                const input = document.getElementById('ai-user-input');
                if (input && window.innerWidth > 640) input.focus();
                scrollAiChatToBottom();
            }, 100);
        } else {
            win.classList.remove('scale-100', 'opacity-100');
            win.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                win.classList.add('hidden');
            }, 250);
            if (iconOpen) iconOpen.classList.remove('hidden');
            if (iconClose) iconClose.classList.add('hidden');
        }
    }

    function scrollAiChatToBottom() {
        const msgs = document.getElementById('ai-chat-messages');
        if (msgs) {
            msgs.scrollTop = msgs.scrollHeight;
        }
    }

    function sendQuickAiQuery(text) {
        const input = document.getElementById('ai-user-input');
        if (input) {
            input.value = text;
            handleAiFormSubmit(new Event('submit'));
        }
    }

    async function handleAiFormSubmit(e) {
        if (e && e.preventDefault) e.preventDefault();
        const input = document.getElementById('ai-user-input');
        const msgs = document.getElementById('ai-chat-messages');
        const indicator = document.getElementById('ai-typing-indicator');
        const sendBtn = document.getElementById('ai-send-btn');

        if (!input || !msgs) return;
        const text = input.value.trim();
        if (!text) return;

        // Clear input
        input.value = '';

        // Append User Bubble
        const userBubble = document.createElement('div');
        userBubble.className = 'flex items-start justify-end gap-2.5';
        userBubble.innerHTML = `
            <div class="max-w-[85%] bg-blue-600 text-white rounded-2xl rounded-tr-sm p-3 shadow-sm text-xs sm:text-[13px] leading-relaxed break-words">
                ${escapeHtml(text)}
            </div>
            <div class="w-7 h-7 rounded-xl bg-blue-500/20 text-blue-400 border border-blue-400/30 flex items-center justify-center shrink-0 mt-0.5">
                <i class="fa-solid fa-user text-xs"></i>
            </div>
        `;
        msgs.appendChild(userBubble);
        scrollAiChatToBottom();

        // Show typing indicator
        if (indicator) indicator.classList.remove('hidden');
        if (sendBtn) sendBtn.disabled = true;

        try {
            const response = await fetch('/api/ai/ask', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ message: text })
            });

            const data = await response.json();
            const replyText = data.reply || 'Asante kwa ujumbe wako. Tafadhali wasiliana na Eng. Mohamedy Hamadi moja kwa moja kwenye WhatsApp +255 777 568 288 kwa msaada wa haraka.';

            // Append AI Bubble
            const aiBubble = document.createElement('div');
            aiBubble.className = 'flex items-start gap-2.5';
            aiBubble.innerHTML = `
                <div class="w-7 h-7 rounded-xl bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-robot text-xs"></i>
                </div>
                <div class="max-w-[85%] bg-slate-800/90 border border-slate-700/80 rounded-2xl rounded-tl-sm p-3.5 shadow-sm text-slate-200 text-xs sm:text-[13px] leading-relaxed">
                    <div class="prose prose-invert text-xs sm:text-[13px] leading-relaxed break-words space-y-1">
                        ${formatAiMarkdown(replyText)}
                    </div>
                </div>
            `;
            msgs.appendChild(aiBubble);
            scrollAiChatToBottom();
        } catch (err) {
            console.error('AI Request error:', err);
            const errBubble = document.createElement('div');
            errBubble.className = 'flex items-start gap-2.5';
            errBubble.innerHTML = `
                <div class="w-7 h-7 rounded-xl bg-red-600/30 border border-red-500/40 text-red-300 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                </div>
                <div class="max-w-[85%] bg-slate-800/90 border border-red-500/40 rounded-2xl rounded-tl-sm p-3 text-red-300 text-xs">
                    Imeshindwa kuunganisha na seva ya AI. Tafadhali wasiliana na Eng. Mohamedy kupitia WhatsApp: <a href="https://wa.me/255777568288" target="_blank" class="underline font-bold text-amber-300">+255 777 568 288</a>.
                </div>
            `;
            msgs.appendChild(errBubble);
            scrollAiChatToBottom();
        } finally {
            if (indicator) indicator.classList.add('hidden');
            if (sendBtn) sendBtn.disabled = false;
        }
    }

    function escapeHtml(string) {
        const entityMap = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;'
        };
        return String(string).replace(/[&<>"'\/]/g, s => entityMap[s]);
    }

    function formatAiMarkdown(text) {
        let escaped = escapeHtml(text);
        // Bold: *text* or **text**
        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong class="text-white font-semibold">$1</strong>');
        escaped = escaped.replace(/\*(.*?)\*/g, '<strong class="text-amber-300 font-semibold">$1</strong>');
        // Phone numbers formatting into clickable tel/wa links
        escaped = escaped.replace(/(\+255\s?\d{3}\s?\d{3}\s?\d{3})/g, '<a href="https://wa.me/$1" target="_blank" class="text-emerald-400 font-bold hover:underline inline-flex items-center gap-1"><i class="fa-brands fa-whatsapp"></i>$1</a>');
        // Emails formatting
        escaped = escaped.replace(/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/g, '<a href="mailto:$1" class="text-blue-400 underline font-medium">$1</a>');
        // New lines to br
        return escaped.replace(/\n/g, '<br>');
    }
</script>
