@extends('layouts.organization')

@section('title', 'تعديل ملف المنظمة')

@section('content')

<div class="mb-4">
    <h1 class="fw-bold mb-1">تعديل ملف المنظمة</h1>
    <p class="text-muted mb-0">قم بتحديث بيانات منظمتك ومعلومات التواصل.</p>
</div>

@if($errors->any())
    <div class="alert alert-danger" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>
        <strong>يرجى تصحيح الأخطاء التالية:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST"
      action="{{ route('organization.profile.update') }}"
      enctype="multipart/form-data"
      novalidate>

    @csrf
    @method('PATCH')

    <div class="panel mb-4">
        <div class="panel-header">
            <h2 class="panel-title">
                <i class="bi bi-building ms-2 text-primary" aria-hidden="true"></i>
                البيانات الأساسية
            </h2>
        </div>

        <div class="panel-body">
            <div class="row g-4">

                <div class="col-md-6">
                    <label for="name" class="form-label">
                        اسم المنظمة <span class="text-danger" aria-hidden="true">*</span>
                    </label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $organization->name) }}"
                           placeholder="الاسم الرسمي للمنظمة"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="phone" class="form-label">
                        رقم الهاتف <span class="text-danger" aria-hidden="true">*</span>
                    </label>
                    <input type="text" name="phone" id="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone', $organization->phone) }}"
                           placeholder="مثال: 05xxxxxxxx"
                           dir="ltr"
                           inputmode="tel"
                           required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="bio" class="form-label">نبذة عن المنظمة</label>
                    <textarea name="bio" id="bio" rows="4"
                              class="form-control @error('bio') is-invalid @enderror"
                              placeholder="اكتب نبذة عن رسالة المنظمة وأهدافها...">{{ old('bio', $organization->bio) }}</textarea>
                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    <div class="panel mb-4">
        <div class="panel-header">
            <h2 class="panel-title">
                <i class="bi bi-image ms-2 text-primary" aria-hidden="true"></i>
                شعار المنظمة
            </h2>
        </div>

        <div class="panel-body">
            <div class="row g-4 align-items-center">

                <div class="col-md-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        @if($organization->logo)
                            <img src="{{ asset('storage/' . $organization->logo) }}"
                                 alt="الشعار الحالي"
                                 class="org-dash-avatar mb-2">
                        @else
                            <div class="org-dash-avatar mb-2" aria-hidden="true">
                                <i class="bi bi-building"></i>
                            </div>
                        @endif
                        <small class="text-muted">الشعار الحالي</small>
                    </div>
                </div>

                <div class="col-md-8">
                    <label for="logo" class="form-label">تغيير الشعار</label>
                    <input type="file" name="logo" id="logo"
                           class="form-control @error('logo') is-invalid @enderror"
                           accept=".jpg,.jpeg,.png,.webp">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                        JPG, JPEG, PNG أو WEBP — الحد الأقصى 2MB. اتركه فارغًا للإبقاء على الشعار الحالي.
                    </small>
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    <div class="panel mb-4">
        <div class="panel-header">
            <h2 class="panel-title">
                <i class="bi bi-geo-alt ms-2 text-primary" aria-hidden="true"></i>
                معلومات التواصل والموقع
            </h2>
        </div>

        <div class="panel-body">
            <div class="row g-4">

                <div class="col-md-6">
                    <label for="email" class="form-label">بريد المنظمة</label>
                    <input type="email" id="email"
                           class="form-control"
                           value="{{ $organization->email }}"
                           disabled>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                        لا يمكن تعديل بريد المنظمة في هذه الصفحة.
                    </small>
                </div>

                <div class="col-md-6">
                    <label for="website" class="form-label">الموقع الإلكتروني</label>
                    <input type="url" name="website" id="website"
                           class="form-control @error('website') is-invalid @enderror"
                           value="{{ old('website', $organization->website) }}"
                           placeholder="https://example.com"
                           dir="ltr">
                    @error('website')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="address" class="form-label">العنوان</label>
                    <input type="text" name="address" id="address"
                           class="form-control @error('address') is-invalid @enderror"
                           value="{{ old('address', $organization->address) }}"
                           placeholder="مثال: شارع النيل، الخرطوم">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="city" class="form-label">
                        المدينة <span class="text-danger" aria-hidden="true">*</span>
                    </label>
                    <input type="text" name="city" id="city"
                           class="form-control @error('city') is-invalid @enderror"
                           value="{{ old('city', $organization->city) }}"
                           placeholder="مثال: الخرطوم"
                           required>
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="country" class="form-label">
                        الدولة <span class="text-danger" aria-hidden="true">*</span>
                    </label>
                    <input type="text" name="country" id="country"
                           class="form-control @error('country') is-invalid @enderror"
                           value="{{ old('country', $organization->country) }}"
                           placeholder="مثال: السودان"
                           required>
                    @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg me-1" aria-hidden="true"></i>
            حفظ التغييرات
        </button>

        <a href="{{ route('organization.profile') }}" class="btn btn-outline-secondary">
            إلغاء
        </a>
    </div>

</form>

@endsection