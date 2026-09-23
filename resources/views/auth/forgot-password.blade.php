<x-guest-layout>
    <x-slot name="title">استعادة كلمة المرور</x-slot>

    <h1 class="auth-card-title">نسيت كلمة المرور؟</h1>
    <p class="auth-card-subtitle">
        لا مشكلة، أخبرنا ببريدك الإلكتروني وسنرسل لك رابطًا لإعادة تعيين كلمة المرور.
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />

            <div class="input-group">
                <span class="input-group-text auth-input-icon" id="forgot-email-addon">
                    <i class="bi bi-envelope" aria-hidden="true"></i>
                </span>

                <x-text-input
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required autofocus
                    placeholder="name@example.com"
                    aria-describedby="forgot-email-addon"
                />
            </div>

            <x-input-error class="mt-1" :messages="$errors->get('email')" />
        </div>

        <div class="d-grid gap-2">
            <x-primary-button>
                {{ __('إرسال رابط إعادة التعيين') }}
            </x-primary-button>
        </div>
    </form>

    <p class="text-center text-muted small mt-3 mb-0">
        <a href="{{ route('login') }}" class="fw-semibold">
            <i class="bi bi-arrow-right me-1"></i>
            العودة إلى تسجيل الدخول
        </a>
    </p>
</x-guest-layout>