<x-layouts::auth :title="__('Admin login')">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_40%),linear-gradient(135deg,#f8fafc_0%,#ecfeff_30%,#f0fdf4_100%)] px-4 py-8">
        <div class="mx-auto max-w-6xl overflow-hidden rounded-[32px] border border-emerald-100 bg-white shadow-[0_30px_80px_rgba(15,23,42,0.12)]">
            <div class="grid lg:grid-cols-[1.15fr_0.85fr]">
                <div class="relative overflow-hidden bg-gradient-to-br from-emerald-900 via-sky-900 to-slate-950 p-8 text-white lg:p-10">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(52,211,153,0.35),_transparent_30%),radial-gradient(circle_at_bottom_right,_rgba(59,130,246,0.25),_transparent_35%)]"></div>

                    <div class="relative z-10 flex h-full flex-col justify-between gap-8">
                        <div>
                            <div class="mb-6 flex items-center gap-4">
                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-white/15 bg-white/10 backdrop-blur-sm">
                                    <svg viewBox="0 0 64 64" class="h-9 w-9" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Geological Survey of Tanzania logo">
                                        <circle cx="32" cy="32" r="30" stroke="rgba(255,255,255,0.7)" stroke-width="2"/>
                                        <path d="M18 42C24 31 27 25 32 18C38 25 41 31 46 42" stroke="white" stroke-width="3" stroke-linecap="round"/>
                                        <path d="M22 36H42" stroke="white" stroke-width="3" stroke-linecap="round"/>
                                        <path d="M32 18V46" stroke="white" stroke-width="3" stroke-linecap="round"/>
                                        <circle cx="32" cy="32" r="3" fill="#a7f3d0"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-200">Geological Survey of Tanzania</p>
                                    <p class="mt-1 text-sm text-sky-100">Admin Portal</p>
                                </div>
                            </div>

                            <h1 class="max-w-md text-4xl font-bold leading-tight lg:text-5xl">Restricted Admin Access</h1>
                            <p class="mt-4 max-w-lg text-base text-slate-200">
                                Only authorized GST administrators can access the admin dashboard and operational management tools.
                            </p>
                        </div>

                        <div class="relative rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                            <div class="mb-3 text-[0.7rem] font-semibold uppercase tracking-[0.3em] text-emerald-200">Security notice</div>
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div class="rounded-xl bg-white/5 p-3">
                                    <div class="text-2xl font-bold text-white">24/7</div>
                                    <div class="text-[0.65rem] text-slate-200 uppercase tracking-wider">Audit</div>
                                </div>
                                <div class="rounded-xl bg-white/5 p-3">
                                    <div class="text-2xl font-bold text-white">Admin</div>
                                    <div class="text-[0.65rem] text-slate-200 uppercase tracking-wider">Access</div>
                                </div>
                                <div class="rounded-xl bg-white/5 p-3">
                                    <div class="text-2xl font-bold text-white">Secure</div>
                                    <div class="text-[0.65rem] text-slate-200 uppercase tracking-wider">Login</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 sm:p-8 lg:p-10">
                    <div class="flex flex-col gap-6">
                        <x-auth-header :title="__('Admin sign in')" :description="__('Enter your admin credentials and access code to continue')" />

                        <x-auth-session-status class="text-center" :status="session('status')" />

                        <div class="mb-2 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                            Admin login requires both your email password and the GST admin access code.
                        </div>

                        <form method="POST" action="{{ route('admin.login.store') }}" class="mx-auto flex w-full max-w-xl flex-col gap-5">
                            @csrf

                            <div class="w-full">
                                <flux:input
                                    class="w-full"
                                    name="email"
                                    :label="__('Admin email')"
                                    :value="old('email')"
                                    type="email"
                                    required
                                    autofocus
                                    autocomplete="email"
                                    placeholder="admin@gst.go.tz"
                                />
                            </div>

                            <div class="w-full">
                                <flux:input
                                    class="w-full"
                                    name="password"
                                    :label="__('Password')"
                                    type="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Password"
                                    viewable
                                />
                            </div>

                            <div class="w-full">
                                <flux:input
                                    class="w-full"
                                    name="admin_code"
                                    :label="__('Admin access code')"
                                    type="password"
                                    required
                                    autocomplete="off"
                                    placeholder="Enter GST admin code"
                                    viewable
                                />
                            </div>

                            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

                            <div class="flex items-center justify-end">
                                <flux:button variant="primary" type="submit" class="w-full" data-test="admin-login-button">
                                    {{ __('Access admin portal') }}
                                </flux:button>
                            </div>
                        </form>

                        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                            <span>{{ __('Not an admin?') }}</span>
                            <flux:link :href="route('login')" wire:navigate>{{ __('User login') }}</flux:link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::auth>
