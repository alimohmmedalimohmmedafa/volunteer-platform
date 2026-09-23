<x-guest-layout>
    <x-slot name="title">إنشاء حساب</x-slot>

    <h1 class="auth-card-title">إنشاء حساب جديد</h1>
    <p class="auth-card-subtitle">انضم كمتطوع وابدأ رحلتك في خدمة مجتمعك.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <x-input-label for="name" :value="__('الاسم الكامل')" />

            <div class="input-group">
                <span class="input-group-text auth-input-icon" id="register-name-addon">
                    <i class="bi bi-person" aria-hidden="true"></i>
                </span>

                <x-text-input
                    id="name"
                    type="text"
                    name="name"
                    :value="old('name')"
                    required autofocus
                    autocomplete="name"
                    placeholder="اسمك الكامل"
                    aria-describedby="register-name-addon"
                />
            </div>

            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />

            <div class="input-group">
                <span class="input-group-text auth-input-icon" id="register-email-addon">
                    <i class="bi bi-envelope" aria-hidden="true"></i>
                </span>

                <x-text-input
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required autocomplete="username"
                    placeholder="name@example.com"
                    aria-describedby="register-email-addon"
                />
            </div>

            <x-input-error class="mt-1" :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-input-label for="password" :value="__('كلمة المرور')" />

            <div class="input-group" x-data="{ show: false }">
                <span class="input-group-text auth-input-icon" id="register-password-addon">
                    <i class="bi bi-shield-lock" aria-hidden="true"></i>
                </span>

                <x-text-input
                    id="password"
                    x-bind:type="show ? 'text' : 'password'"
                    name="password"
                    required autocomplete="new-password"
                    placeholder="••••••••"
                    aria-describedby="register-password-addon register-password-help"
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

            <div id="register-password-help" class="form-text">
                {{ __('8 أحرف على الأقل، وتتضمن حروفًا وأرقامًا.') }}
            </div>

            <x-input-error class="mt-1" :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />

            <div class="input-group" x-data="{ show: false }">
                <span class="input-group-text auth-input-icon" id="register-confirm-addon">
                    <i class="bi bi-shield-check" aria-hidden="true"></i>
                </span>

                <x-text-input
                    id="password_confirmation"
                    x-bind:type="show ? 'text' : 'password'"
                    name="password_confirmation"
                    required autocomplete="new-password"
                    placeholder="••••••••"
                    aria-describedby="register-confirm-addon"
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
                {{ __('إنشاء الحساب') }}
            </x-primary-button>
        </div>
    </form>

    <div class="auth-switch">
        <span class="text-muted small">لديك حساب بالفعل؟</span>
        <a href="{{ route('login') }}" class="fw-semibold small">
            <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
            تسجيل الدخول
        </a>
    </div>

    <div class="text-center mt-3">
        <a href="{{ route('organization.register') }}" class="auth-org-link small">
            <i class="bi bi-building me-1" aria-hidden="true"></i>
            هل تمثل منظمة؟ سجّل منظمتك
        </a>
    </div>
</x-guest-layout>