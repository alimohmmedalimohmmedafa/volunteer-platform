@extends('layouts.admin')

@section('title', 'إضافة منظمة')

@section('content')

<div class="mb-4">

    <a href="{{ route('admin.organizations.index') }}"
       class="btn btn-link mb-2 px-0">
        <i class="bi bi-arrow-right"></i>
        العودة إلى المنظمات
    </a>

    <h2 class="fw-bold mb-1">إضافة منظمة جديدة</h2>

    <p class="text-muted mb-0">
        سيتم إنشاء حساب المنظمة وتفعيلها واعتمادها مباشرة.
    </p>

</div>

<div class="col-lg-11">
<div class="card border-0 shadow-sm">

    <div class="card-body p-4">

        <form method="POST"
              action="{{ route('admin.organizations.store') }}">

            @csrf

            <h5 class="fw-bold mb-3 text-primary">
                <i class="bi bi-person-circle me-1"></i>
                بيانات الحساب
            </h5>

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label">اسم المنظمة <span class="text-danger">*</span></label>

                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror">

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>

                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror">

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>

                    <input type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror">

                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>

                    <input type="password"
                           name="password_confirmation"
                           class="form-control">

                </div>

            </div>

            <hr>

            <h5 class="fw-bold mb-3 text-primary">
                <i class="bi bi-building me-1"></i>
                بيانات المنظمة
            </h5>

            <div class="row g-3">

                <div class="col-md-4">

                    <label class="form-label">الهاتف <span class="text-danger">*</span></label>

                    <input type="text"
                           name="phone"
                           value="{{ old('phone') }}"
                           class="form-control @error('phone') is-invalid @enderror">

                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-4">

                    <label class="form-label">المدينة <span class="text-danger">*</span></label>

                    <input type="text"
                           name="city"
                           value="{{ old('city') }}"
                           class="form-control @error('city') is-invalid @enderror">

                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-4">

                    <label class="form-label">الدولة <span class="text-danger">*</span></label>

                    <input type="text"
                           name="country"
                           value="{{ old('country') }}"
                           class="form-control @error('country') is-invalid @enderror">

                    @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">الموقع الإلكتروني</label>

                    <input type="url"
                           name="website"
                           value="{{ old('website') }}"
                           class="form-control @error('website') is-invalid @enderror">

                    @error('website')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">العنوان</label>

                    <input type="text"
                           name="address"
                           value="{{ old('address') }}"
                           class="form-control @error('address') is-invalid @enderror">

                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-12">

                    <label class="form-label">نبذة عن المنظمة</label>

                    <textarea name="bio"
                              rows="3"
                              class="form-control @error('bio') is-invalid @enderror">{{ old('bio') }}</textarea>

                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

            </div>

            <div class="mt-4">

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i>
                    إضافة المنظمة
                </button>

            </div>

        </form>

    </div>

</div>
</div>

@endsection