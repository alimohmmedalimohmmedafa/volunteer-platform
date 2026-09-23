<x-guest-layout>
    <x-slot name="title">تأكيد كلمة المرور</x-slot>

    <h1 class="auth-card-title">هذه منطقة آمنة</h1>
    <p class="auth-card-subtitle">
        يرجى تأكيد كلمة المرور الخاصة بك قبل المتابعة.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('كلمة المرور')" />

            <div class="input-group" x-data="{ show: false }">
                <span class="input-group-text auth-input-icon" id="confirm-password-addon">
                    <i class="bi bi-shield-lock" aria-hidden="true"></i>
                </span>

                <x-text-input
                    id="password"
                    x-bind:type="show ? 'text' : 'password'"
                    name="password"
                    required autocomplete="current-password"
                    placeholder="••••••••"
                    aria-describedby="confirm-password-addon"
                />

                <button
                    type="button"
                    class="btn btn-outline-secondary auth-password-toggle"
                    @click="show = !show"
                    :aria-pressed="show.toString()"
                    :aria-label="show ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور'"
                >
                    <i class="bi" :class="show ? 'bi-eye-slash' : 'bi-eye'" aria-hidden="true"></i>
                </button>
            </div>

            <x-input-error class="mt-1" :messages="$errors->get('password')" />
        </div>

        <div class="d-grid gap-2">
            <x-primary-button>
                {{ __('تأكيد') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>