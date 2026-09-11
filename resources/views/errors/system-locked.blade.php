<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Locked - CBE Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full bg-slate-900 border border-slate-800 rounded-3xl p-8 text-center shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-red-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-blue-600/20 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <div class="w-20 h-20 bg-red-500/10 border border-red-500/30 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl shadow-inner">
                <i class="fa-solid fa-lock"></i>
            </div>

            <span class="px-3 py-1 bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-black uppercase tracking-widest rounded-full inline-block mb-3">
                Security Lockout Active
            </span>

            <h1 class="text-2xl font-black text-white tracking-tight mb-2">MFUMO UMEZIMWA KWA MUDA</h1>
            
            <p class="text-xs text-slate-400 leading-relaxed mb-6">
                {{ $reason ?? 'Mfumo huu umezimwa na Mwenye Mfumo (Super Admin). Hakuna mtumiaji yeyote anayeruhusiwa kuingia au kufanya mabadiliko bila idhini yake.' }}
            </p>

            <div class="p-4 bg-slate-800/80 rounded-2xl border border-slate-700/50 text-left mb-6 text-xs space-y-2">
                <div class="flex items-center justify-between text-[11px]">
                    <span class="text-slate-400">System Developer / Owner:</span>
                    <span class="font-bold text-white">{{ $owner ?? 'MOHAMEDY HAMADI MOHAMED' }}</span>
                </div>
                <div class="flex items-center justify-between text-[11px]">
                    <span class="text-slate-400">Owner Contact:</span>
                    <span class="font-mono text-blue-400">{{ $email ?? 'mh4409684@gmail.com' }}</span>
                </div>
                <div class="flex items-center justify-between text-[11px]">
                    <span class="text-slate-400">License Verification:</span>
                    <span class="text-emerald-400 font-mono font-bold">CBE-PROPRIETARY-PROTECTED</span>
                </div>
            </div>

            <a href="{{ route('cbe.login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition shadow-lg shadow-blue-600/20">
                <i class="fa-solid fa-shield-halved"></i> Owner Sign-in Verification
            </a>
        </div>
    </div>
</body>
</html>