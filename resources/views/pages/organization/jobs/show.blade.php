@extends('layouts.organization')

@section('title', $job->title)

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="fw-bold mb-1">{{ $job->title }}</h1>

        <span class="status-badge status-{{ $job->status }}">
            @if($job->status === 'published')
                <i class="bi bi-megaphone" aria-hidden="true"></i> منشورة
            @elseif($job->status === 'completed')
                <i class="bi bi-check-circle" aria-hidden="true"></i> مكتملة
            @else
                <i class="bi bi-x-circle" aria-hidden="true"></i> ملغاة
            @endif
        </span>
    </div>

    <a href="{{ route('organization.jobs.edit', $job) }}" class="btn btn-outline-primary">
        <i class="bi bi-pencil me-1" aria-hidden="true"></i>
        تعديل
    </a>
</div>

<div class="row g-4">

    <div class="col-lg-8">

        <div class="panel mb-4">
            <div class="panel-header">
                <h2 class="panel-title">
                    <i class="bi bi-card-text ms-2 text-primary" aria-hidden="true"></i>
                    وصف الفرصة
                </h2>
            </div>

            <div class="panel-body">
                <p class="mb-0">{!! nl2br(e($job->description)) !!}</p>
            </div>
        </div>

        @if($job->requirements)
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">
                        <i class="bi bi-list-check ms-2 text-primary" aria-hidden="true"></i>
                        المتطلبات
                    </h2>
                </div>

                <div class="panel-body">
                    <p class="mb-0">{!! nl2br(e($job->requirements)) !!}</p>
                </div>
            </div>
        @endif

    </div>

    <div class="col-lg-4">

        <div class="panel mb-4">
            <div class="panel-header">
                <h2 class="panel-title">
                    <i class="bi bi-info-circle ms-2 text-primary" aria-hidden="true"></i>
                    معلومات الفرصة
                </h2>
            </div>

            <div class="panel-body">

                <div class="row g-3">

                    <div class="col-6 col-lg-12">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-geo-alt" aria-hidden="true"></i> الموقع
                            </div>
                            <div class="info-value">{{ $job->location }}</div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-12">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-calendar-plus" aria-hidden="true"></i> تاريخ البداية
                            </div>
                            <div class="info-value">{{ $job->start_date?->format('d M Y') }}</div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-12">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-calendar-x" aria-hidden="true"></i> تاريخ النهاية
                            </div>
                            <div class="info-value">{{ $job->end_date?->format('d M Y') }}</div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-12">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-calendar-event" aria-hidden="true"></i> آخر موعد
                            </div>
                            <div class="info-value">{{ $job->application_deadline?->format('d M Y') }}</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-people" aria-hidden="true"></i> عدد المتقدمين
                            </div>
                            <div class="info-value">{{ $job->applications_count }}</div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        @if($job->status === 'published')

            <div class="d-grid gap-2">

                <button type="button"
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#completeJobModal">
                    <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                    تحديد الفرصة كمكتملة
                </button>

                <button type="button"
                        class="btn btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#cancelJobModal">
                    <i class="bi bi-x-circle me-1" aria-hidden="true"></i>
                    إلغاء الفرصة
                </button>

            </div>

        @elseif($job->status === 'cancelled')

            <div class="alert alert-danger d-flex align-items-center gap-2 mb-0" role="alert">
                <i class="bi bi-x-circle" aria-hidden="true"></i>
                هذه الفرصة ملغاة.
            </div>

        @elseif($job->status === 'completed')

            <div class="alert alert-success d-flex align-items-center gap-2 mb-0" role="alert">
                <i class="bi bi-check-circle" aria-hidden="true"></i>
                هذه الفرصة مكتملة.
            </div>

        @endif

    </div>

</div>

{{-- Complete confirmation modal --}}
<div class="modal fade confirm-modal" id="completeJobModal" tabindex="-1"
     aria-labelledby="completeJobModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="modal-icon accept mb-3">
                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                </div>
                <h5 class="fw-bold mb-2">تحديد الفرصة كمكتملة؟</h5>
                <p class="text-muted mb-4">هل أنت متأكد من تحديد فرصة "{{ $job->title }}" كمكتملة؟</p>

                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        إلغاء
                    </button>

                    <form method="POST" action="{{ route('organization.jobs.complete', $job) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                            تأكيد الإكمال
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Cancel confirmation modal --}}
<div class="modal fade confirm-modal" id="cancelJobModal" tabindex="-1"
     aria-labelledby="cancelJobModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="modal-icon reject mb-3">
                    <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
                </div>
                <h5 class="fw-bold mb-2">إلغاء الفرصة؟</h5>
                <p class="text-muted mb-4">هل أنت متأكد من إلغاء فرصة "{{ $job->title }}"؟</p>

                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        تراجع
                    </button>

                    <form method="POST" action="{{ route('organization.jobs.cancel', $job) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle me-1" aria-hidden="true"></i>
                            تأكيد الإلغاء
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection