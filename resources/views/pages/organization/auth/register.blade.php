<x-guest-layout>
    <x-slot name="title">تسجيل منظمة جديدة</x-slot>

    <h1 class="auth-card-title">تسجيل منظمة جديدة</h1>
    <p class="auth-card-subtitle">
        أنشئ حساب المنظمة وأرسل طلبك للمراجعة.
    </p>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2" aria-hidden="true"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>
            <strong>يرجى تصحيح الأخطاء التالية:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('organization.register.store') }}">
        @csrf

        <h5 class="mb-3">بيانات الحساب</h5>

        <div class="mb-3">
            <label for="name" class="form-label">اسم المسؤول</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني للحساب</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">كلمة المرور</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <hr class="my-4">

        <h5 class="mb-3">بيانات المنظمة</h5>

        <div class="mb-3">
            <label for="organization_name" class="form-label">اسم المنظمة</label>
            <input type="text" id="organization_name" name="organization_name" class="form-control" value="{{ old('organization_name') }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="organization_email" class="form-label">بريد المنظمة</label>
                <input type="email" id="organization_email" name="organization_email" class="form-control" value="{{ old('organization_email') }}">
            </div>
        </div>

        <div class="mb-3">
            <label for="bio" class="form-label">نبذة عن المنظمة</label>
            <textarea id="bio" name="bio" class="form-control" rows="4">{{ old('bio') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="website" class="form-label">الموقع الإلكتروني</label>
            <input type="text" id="website" name="website" class="form-control" value="{{ old('website') }}" placeholder="https://example.com">
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">العنوان</label>
            <input type="text" id="address" name="address" class="form-control" value="{{ old('address') }}">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="city" class="form-label">المدينة</label>
                <input type="text" id="city" name="city" class="form-control" value="{{ old('city') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="country" class="form-label">الدولة</label>
                <input type="text" id="country" name="country" class="form-control" value="{{ old('country') }}">
            </div>
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                إرسال طلب التسجيل
            </button>
        </div>
    </form>

    <p class="text-center text-muted small mt-4 mb-0">
        لديك حساب بالفعل؟
        <a href="{{ route('login') }}" class="fw-semibold">تسجيل الدخول</a>
    </p>
</x-guest-layout>