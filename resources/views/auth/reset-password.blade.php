<x-guest-layout>
    <x-slot name="title">إعادة تعيين كلمة المرور</x-slot>

    <h1 class="auth-card-title">إعادة تعيين كلمة المرور</h1>
    <p class="auth-card-subtitle">أدخل كلمة مرور جديدة لحسابك.</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />

            <div class="input-group">
                <span class="input-group-text auth-input-icon" id="reset-email-addon">
                    <i class="bi bi-envelope" aria-hidden="true"></i>
                </span>

                <x-text-input
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email', $request->email)"
                    required autofocus autocomplete="username"
                    aria-describedby="reset-email-addon"
                />
            </div>

            <x-input-error class="mt-1" :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-input-label for="password" :value="__('كلمة المرور الجديدة')" />

            <div class="input-group" x-data="{ show: false }">
                <span class="input-group-text auth-input-icon" id="reset-password-addon">
                    <i class="bi bi-shield-lock" aria-hidden="true"></i>
                </span>

                <x-text-input
                    id="password"
                    x-bind:type="show ? 'text' : 'password'"
                    name="password"
                    required autocomplete="new-password"
                    placeholder="••••••••"
                    aria-describedby="reset-password-addon"
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

        <!-- Confirm Password -->
        <div class="mb-4">
            <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />

            <div class="input-group" x-data="{ show: false }">
                <span class="input-group-text auth-input-icon" id="reset-confirm-addon">
                    <i class="bi bi-shield-check" aria-hidden="true"></i>
                </span>

                <x-text-input
                    id="password_confirmation"
                    x-bind:type="show ? 'text' : 'password'"
                    name="password_confirmation"
                    required autocomplete="new-password"
                    placeholder="••••••••"
                    aria-describedby="reset-confirm-addon"
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

            <x-input-error class="mt-1" :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="d-grid gap-2">
            <x-primary-button>
                {{ __('إعادة تعيين كلمة المرور') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>