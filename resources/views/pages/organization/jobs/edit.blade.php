@extends('layouts.organization')

@section('title', 'تعديل فرصة التطوع')

@section('content')

<div class="mb-4">
    <h1 class="fw-bold mb-1">تعديل فرصة التطوع</h1>
    <p class="text-muted mb-0">حدّث معلومات فرصة "{{ $job->title }}".</p>
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

<form method="POST" action="{{ route('organization.jobs.update', $job) }}" novalidate>

    @csrf
    @method('PATCH')

    <div class="panel mb-4">
        <div class="panel-header">
            <h2 class="panel-title">
                <i class="bi bi-card-text ms-2 text-primary" aria-hidden="true"></i>
                المعلومات الأساسية
            </h2>
        </div>

        <div class="panel-body">
            <div class="row g-4">

                <div class="col-12">
                    <label for="title" class="form-label">
                        عنوان الفرصة <span class="text-danger" aria-hidden="true">*</span>
                    </label>
                    <input type="text" name="title" id="title"
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $job->title) }}"
                           placeholder="مثال: تنظيم فعالية تطوعية"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="location" class="form-label">
                        الموقع <span class="text-danger" aria-hidden="true">*</span>
                    </label>
                    <input type="text" name="location" id="location"
                           class="form-control @error('location') is-invalid @enderror"
                           value="{{ old('location', $job->location) }}"
                           placeholder="مثال: الخرطوم"
                           required>
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    <div class="panel mb-4">
        <div class="panel-header">
            <h2 class="panel-title">
                <i class="bi bi-calendar-event ms-2 text-primary" aria-hidden="true"></i>
                التواريخ
            </h2>
        </div>

        <div class="panel-body">
            <div class="row g-4">

                <div class="col-md-4">
                    <label for="start_date" class="form-label">
                        تاريخ البداية <span class="text-danger" aria-hidden="true">*</span>
                    </label>
                    <input type="date" name="start_date" id="start_date"
                           class="form-control @error('start_date') is-invalid @enderror"
                           value="{{ old('start_date', $job->start_date?->format('Y-m-d')) }}"
                           required>
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="end_date" class="form-label">
                        تاريخ النهاية <span class="text-danger" aria-hidden="true">*</span>
                    </label>
                    <input type="date" name="end_date" id="end_date"
                           class="form-control @error('end_date') is-invalid @enderror"
                           value="{{ old('end_date', $job->end_date?->format('Y-m-d')) }}"
                           required>
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="application_deadline" class="form-label">
                        آخر موعد للتقديم <span class="text-danger" aria-hidden="true">*</span>
                    </label>
                    <input type="date" name="application_deadline" id="application_deadline"
                           class="form-control @error('application_deadline') is-invalid @enderror"
                           value="{{ old('application_deadline', $job->application_deadline?->format('Y-m-d')) }}"
                           required>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                        آخر يوم لاستقبال الطلبات.
                    </small>
                    @error('application_deadline')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    <div class="panel mb-4">
        <div class="panel-header">
            <h2 class="panel-title">
                <i class="bi bi-list-check ms-2 text-primary" aria-hidden="true"></i>
                التفاصيل
            </h2>
        </div>

        <div class="panel-body">
            <div class="row g-4">

                <div class="col-12">
                    <label for="description" class="form-label">
                        وصف الفرصة <span class="text-danger" aria-hidden="true">*</span>
                    </label>
                    <textarea name="description" id="description" rows="5"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="اكتب وصفًا واضحًا للفرصة..."
                              required>{{ old('description', $job->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="requirements" class="form-label">المتطلبات</label>
                    <textarea name="requirements" id="requirements" rows="5"
                              class="form-control @error('requirements') is-invalid @enderror"
                              placeholder="اكتب متطلبات المشاركة في الفرصة...">{{ old('requirements', $job->requirements) }}</textarea>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                        اختياري — صف ما يلزم المتطوع للانضمام.
                    </small>
                    @error('requirements')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg me-1" aria-hidden="true"></i>
            حفظ التعديلات
        </button>

        <a href="{{ route('organization.jobs.show', $job) }}" class="btn btn-outline-secondary">
            إلغاء
        </a>
    </div>

</form>

@endsection