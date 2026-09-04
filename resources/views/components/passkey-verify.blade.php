@props([
    'optionsRoute' => 'passkey.login-options',
    'submitRoute' => 'passkey.login',
    'label' => __('Sign in with a passkey'),
    'loadingLabel' => __('Authenticating...'),
    'separator' => __('Or continue with email'),
])

<div
    x-data="{
        supported: false,
        loading: false,
        error: null,
        updateSupport() {
            this.supported = Boolean(window.Passkeys?.isSupported());
        },
        async verify() {
            this.loading = true;
            this.error = null;

            try {
                const response = await window.Passkeys.authenticate();
                window.location.href = response.redirect;
            } catch (e) {
                this.error = e.message;
                this.loading = false;
            }
        }
    }"
    x-init="updateSupport()"
>
    <div x-show="supported" x-cloak class="mt-4">
        <div class="relative flex items-center justify-center">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-zinc-200 dark:border-zinc-700"></div>
            </div>

            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-white px-2 text-zinc-500 dark:bg-zinc-900 dark:text-zinc-400">
                    {{ $separator }}
                </span>
            </div>
        </div>

        <div class="mt-4">
            <button
                type="button"
                x-on:click="verify"
                x-bind:disabled="loading"
                class="w-full justify-center px-4 py-2 bg-black text-white rounded-md"
            >
                <span x-show="!loading">{{ $label }}</span>
                <span x-show="loading" x-cloak>{{ $loadingLabel }}</span>
            </button>
        </div>

        <template x-if="error">
            <p class="mt-2 text-sm text-red-600 dark:text-red-400" x-text="error"></p>
        </template>
    </div>
</div>