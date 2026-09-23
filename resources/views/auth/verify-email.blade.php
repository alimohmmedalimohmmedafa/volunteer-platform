<x-guest-layout>
    <x-slot name="title">تفعيل البريد الإلكتروني</x-slot>

    <h1 class="auth-card-title">تحقق من بريدك الإلكتروني</h1>
    <p class="auth-card-subtitle">
        شكرًا لتسجيلك! قبل البدء، يرجى التحقق من بريدك الإلكتروني
        عبر الضغط على الرابط الذي أرسلناه لك. إذا لم تستلم البريد،
        يسعدنا إرساله لك مرة أخرى.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2" aria-hidden="true"></i>
            تم إرسال رابط تحقق جديد إلى البريد الذي سجلت به.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    @endif

    <div class="mt-4 d-flex flex-column flex-sm-row justify-content-between gap-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-primary-button>
                <i class="bi bi-envelope-arrow-up me-1"></i>
                {{ __('إعادة إرسال رابط التفعيل') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn btn-outline-secondary w-100">
                <i class="bi bi-box-arrow-right me-1"></i>
                {{ __('تسجيل الخروج') }}
            </button>
        </form>
    </div>
</x-guest-layout>