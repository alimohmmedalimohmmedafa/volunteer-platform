@extends('layouts.volunteer')

@section('title', 'تعديل الملف الشخصي')

@section('content')

<div class="mb-4">
    <h1 class="fw-bold mb-1">تعديل الملف الشخصي</h1>
    <p class="text-muted mb-0">قم بتحديث معلوماتك الشخصية ومهاراتك وسيرتك الذاتية.</p>
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
      action="{{ route('volunteer.profile.update') }}"
      enctype="multipart/form-data"
      novalidate>

    @csrf
    @method('PUT')

    <div class="panel mb-4">
        <div class="panel-header">
            <h2 class="panel-title">
                <i class="bi bi-person ms-2 text-primary" aria-hidden="true"></i>
                المعلومات الشخصية
            </h2>
        </div>

        <div class="panel-body">
            <div class="row g-4">

                {{-- Name --}}
                <div class="col-md-6">
                    <label for="name" class="form-label">
                        الاسم <span class="text-danger" aria-hidden="true">*</span>
                    </label>
                    <input type="text" name="name" id="name"
                           class="form-control"
                           value="{{ old('name', $user->name) }}"
                           placeholder="اسمك الكامل"
                           required>
                    @error('name')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="bi bi-exclamation-circle me-1" aria-hidden="true"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Email (read-only) --}}
                <div class="col-md-6">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" id="email"
                           class="form-control"
                           value="{{ $user->email }}"
                           disabled>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                        لا يمكن تعديل البريد الإلكتروني في هذه الصفحة.
                    </small>
                </div>

                {{-- Phone --}}
                <div class="col-md-6">
                    <label for="phone" class="form-label">رقم الهاتف</label>
                    <input type="text" name="phone" id="phone"
                           class="form-control"
                           value="{{ old('phone', $profile?->phone) }}"
                           placeholder="مثال: 05xxxxxxxx"
                           dir="ltr"
                           inputmode="tel">
                    @error('phone')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- City --}}
                <div class="col-md-3">
                    <label for="city" class="form-label">المدينة</label>
                    <input type="text" name="city" id="city"
                           class="form-control"
                           value="{{ old('city', $profile?->city) }}"
                           placeholder="مثال: الرياض">
                    @error('city')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Country --}}
                <div class="col-md-3">
                    <label for="country" class="form-label">الدولة</label>
                    <input type="text" name="country" id="country"
                           class="form-control"
                           value="{{ old('country', $profile?->country) }}"
                           placeholder="مثال: السعودية">
                    @error('country')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Photo --}}
                <div class="col-md-6">
                    <label for="photo" class="form-label">الصورة الشخصية</label>
                    <input type="file" name="photo" id="photo"
                           class="form-control"
                           accept=".jpg,.jpeg,.png,.webp">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                        JPG, JPEG, PNG أو WEBP — الحد الأقصى 2MB.
                    </small>
                    @error('photo')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Bio --}}
                <div class="col-12">
                    <label for="bio" class="form-label">نبذة عني</label>
                    <textarea name="bio" id="bio" rows="5"
                              class="form-control"
                              placeholder="اكتب نبذة قصيرة عنك، خبراتك، واهتماماتك التطوعية...">{{ old('bio', $profile?->bio) }}</textarea>
                    @error('bio')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    <div class="panel mb-4">
        <div class="panel-header">
            <h2 class="panel-title">
                <i class="bi bi-stars ms-2 text-primary" aria-hidden="true"></i>
                المهارات
            </h2>
        </div>

        <div class="panel-body">
            <label for="skills" class="form-label">المهارات</label>
            <textarea name="skills" id="skills" rows="4"
                      class="form-control"
                      placeholder="مثال: PHP, Laravel, MySQL, العمل الجماعي, التواصل">{{ old('skills', $profile?->skills) }}</textarea>
            <small class="text-muted">
                <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                اكتب مهاراتك وافصل بينها بفاصلة.
            </small>
            @error('skills')
                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="panel mb-4">
        <div class="panel-header">
            <h2 class="panel-title">
                <i class="bi bi-file-earmark-pdf ms-2 text-primary" aria-hidden="true"></i>
                السيرة الذاتية
            </h2>
        </div>

        <div class="panel-body">
            <label for="cv" class="form-label">الملف</label>
            <input type="file" name="cv" id="cv"
                   class="form-control"
                   accept=".pdf,application/pdf">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                PDF فقط — الحد الأقصى 5MB.
            </small>
            @error('cv')
                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
            @enderror

            @if($profile?->cv)
                <div class="mt-3 d-flex flex-wrap gap-2">
                    <a href="{{ asset('storage/' . $profile->cv) }}" target="_blank"
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye me-1" aria-hidden="true"></i> عرض الحالية
                    </a>
                    <a href="{{ asset('storage/' . $profile->cv) }}" download
                       class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-download me-1" aria-hidden="true"></i> تحميل
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg me-1" aria-hidden="true"></i>
            حفظ التغييرات
        </button>

        <a href="{{ route('volunteer.profile') }}" class="btn btn-outline-secondary">
            إلغاء
        </a>
    </div>

</form>

@endsection