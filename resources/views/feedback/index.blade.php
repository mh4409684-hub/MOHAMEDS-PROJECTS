<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Review</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-[radial-gradient(circle_at_top,_#ecfdf5,_#dff5ee_32%,_#e1f3f7_70%,_#f5f9f8_100%)] text-slate-800">
    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-700">Geological Survey of Tanzania</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Feedback review</h1>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Back to dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($feedback as $item)
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $item->name ?: 'Anonymous user' }}</p>
                            <p class="text-xs text-slate-500">{{ $item->email ?: 'No email provided' }}</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="rounded-full bg-sky-100 px-2.5 py-1 text-xs font-semibold text-sky-700">{{ ucfirst($item->type) }}</span>
                            <span class="rounded-full {{ $item->status === 'done' ? 'bg-emerald-100 text-emerald-700' : ($item->status === 'processed' ? 'bg-amber-100 text-amber-700' : ($item->status === 'seen' ? 'bg-sky-100 text-sky-700' : 'bg-slate-200 text-slate-700')) }} px-2.5 py-1 text-xs font-semibold">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm leading-7 text-slate-700">{{ $item->message }}</p>
                    </div>

                    <form action="{{ route('feedback.update', $item) }}" method="POST" class="mt-4 space-y-3">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Admin notes</label>
                            <textarea name="notes" rows="3" placeholder="Write response, action taken, or support update..." class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200">{{ old('notes', $item->notes) }}</textarea>
                        </div>

                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <select name="status" class="rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200">
                                <option value="new" {{ $item->status === 'new' ? 'selected' : '' }}>New</option>
                                <option value="seen" {{ $item->status === 'seen' ? 'selected' : '' }}>Seen</option>
                                <option value="processed" {{ $item->status === 'processed' ? 'selected' : '' }}>Processed</option>
                                <option value="done" {{ $item->status === 'done' ? 'selected' : '' }}>Done</option>
                            </select>

                            <button type="submit" class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                                Save status
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500">
                    No feedback or complaints have been submitted yet.
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>
