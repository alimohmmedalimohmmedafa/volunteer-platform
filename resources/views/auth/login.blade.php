<x-guest-layout>
    <x-slot name="title">تسجيل الدخول</x-slot>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <h1 class="auth-card-title">تسجيل الدخول</h1>
    <p class="auth-card-subtitle">مرحبًا بعودتك! سجّل دخولك للمتابعة.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />

            <div class="input-group">
                <span class="input-group-text auth-input-icon" id="login-email-addon">
                    <i class="bi bi-envelope" aria-hidden="true"></i>
                </span>

                <x-text-input
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required autofocus
                    autocomplete="username"
                    placeholder="name@example.com"
                    aria-describedby="login-email-addon"
                />
            </div>

            <x-input-error class="mt-1" :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <x-input-label for="password" :value="__('كلمة المرور')" />

                @if (Route::has('password.request'))
                    <a class="small fw-semibold" href="{{ route('password.request') }}">
                        {{ __('نسيت كلمة المرور؟') }}
                    </a>
                @endif
            </div>

            <div class="input-group" x-data="{ show: false }">
                <span class="input-group-text auth-input-icon" id="login-password-addon">
                    <i class="bi bi-shield-lock" aria-hidden="true"></i>
                </span>

                <x-text-input
                    id="password"
                    x-bind:type="show ? 'text' : 'password'"
                    name="password"
                    required autocomplete="current-password"
                    placeholder="••••••••"
                    aria-describedby="login-password-addon"
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

        <!-- Remember Me -->
        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label text-muted">{{ __('تذكرني') }}</label>
        </div>

        <div class="d-grid gap-2">
            <x-primary-button>
                {{ __('تسجيل الدخول') }}
            </x-primary-button>
        </div>
    </form>

    <div class="auth-switch">
        <svg width="1" height="18" aria-hidden="true"><line x1="0" y1="0" x2="0" y2="18" stroke="#e2e8f0" /></svg>
        <span class="text-muted small">ليس لديك حساب؟</span>
        <a href="{{ route('register') }}" class="fw-semibold small">
            <i class="bi bi-person-plus me-1" aria-hidden="true"></i>
            إنشاء حساب جديد
        </a>
    </div>
</x-guest-layout>