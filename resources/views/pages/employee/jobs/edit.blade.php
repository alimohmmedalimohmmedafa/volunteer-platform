@extends('layouts.employee')

@section('title', 'تعديل وظيفة')

@section('content')

<div class="mb-4">

    <a href="{{ route('employee.jobs.index') }}"
       class="btn btn-link mb-2 px-0">
        <i class="bi bi-arrow-right"></i>
        العودة إلى الوظائف
    </a>

    <h2 class="fw-bold mb-1">تعديل وظيفة</h2>

    <p class="text-muted mb-0">{{ $job->organization->name }} · {{ $job->title }}</p>

</div>

<div class="card border-0 shadow-sm">

    <div class="card-body p-4">

        <form method="POST"
              action="{{ route('employee.jobs.update', $job) }}">

            @csrf
            @method('PATCH')

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label">عنوان الوظيفة <span class="text-danger">*</span></label>

                    <input type="text"
                           name="title"
                           value="{{ old('title', $job->title) }}"
                           class="form-control @error('title') is-invalid @enderror">

                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">الموقع <span class="text-danger">*</span></label>

                    <input type="text"
                           name="location"
                           value="{{ old('location', $job->location) }}"
                           class="form-control @error('location') is-invalid @enderror">

                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-12">

                    <label class="form-label">المتطلبات <span class="text-danger">*</span></label>

                    <input type="text"
                           name="requirements"
                           value="{{ old('requirements', $job->requirements) }}"
                           class="form-control @error('requirements') is-invalid @enderror">

                    @error('requirements')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-4">

                    <label class="form-label">تاريخ البداية <span class="text-danger">*</span></label>

                    <input type="date"
                           name="start_date"
                           value="{{ old('start_date', optional($job->start_date)->format('Y-m-d')) }}"
                           class="form-control @error('start_date') is-invalid @enderror">

                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-4">

                    <label class="form-label">تاريخ النهاية <span class="text-danger">*</span></label>

                    <input type="date"
                           name="end_date"
                           value="{{ old('end_date', optional($job->end_date)->format('Y-m-d')) }}"
                           class="form-control @error('end_date') is-invalid @enderror">

                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-4">

                    <label class="form-label">آخر موعد للتقديم <span class="text-danger">*</span></label>

                    <input type="date"
                           name="application_deadline"
                           value="{{ old('application_deadline', optional($job->application_deadline)->format('Y-m-d')) }}"
                           class="form-control @error('application_deadline') is-invalid @enderror">

                    @error('application_deadline')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-12">

                    <label class="form-label">الوصف <span class="text-danger">*</span></label>

                    <textarea name="description"
                              rows="4"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $job->description) }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

            </div>

            <div class="mt-4">

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i>
                    حفظ التعديلات
                </button>

            </div>

        </form>

    </div>

</div>

@endsection