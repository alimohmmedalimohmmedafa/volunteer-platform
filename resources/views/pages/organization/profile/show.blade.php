@extends('layouts.organization')

@section('title', 'ملف المنظمة')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="fw-bold mb-0">ملف المنظمة</h1>
        <p class="text-muted mb-0">بيانات المنظمة ومعلومات التواصل.</p>
    </div>

    <a href="{{ route('organization.profile.edit') }}" class="btn btn-primary">
        <i class="bi bi-pencil me-1" aria-hidden="true"></i>
        تعديل البيانات
    </a>
</div>

{{-- Header --}}
<div class="panel overflow-hidden mb-4">
    <div class="profile-cover"></div>

    <div class="profile-identity">
        @if($organization->logo)
            <img src="{{ asset('storage/' . $organization->logo) }}"
                 alt="شعار {{ $organization->name }}"
                 class="profile-avatar">
        @else
            <div class="profile-avatar" aria-hidden="true">
                <i class="bi bi-building"></i>
            </div>
        @endif

        <div class="flex-grow-1 pb-2">
            <h2 class="profile-name d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-2">
                {{ $organization->name }}
                <span class="badge bg-success">
                    <i class="bi bi-patch-check-fill me-1" aria-hidden="true"></i>
                    معتمدة
                </span>
            </h2>

            <div class="profile-location">
                @if($organization->city || $organization->country)
                    <i class="bi bi-geo-alt me-1" aria-hidden="true"></i>
                    {{ trim(($organization->city ?? '') . ' ' . ($organization->country ?? '')) }}
                @elseif($organization->address)
                    <i class="bi bi-geo-alt me-1" aria-hidden="true"></i>
                    {{ $organization->address }}
                @else
                    <i class="bi bi-envelope me-1" aria-hidden="true"></i>
                    {{ $organization->email }}
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Bio --}}
<div class="panel mb-4">
    <div class="panel-header">
        <h2 class="panel-title">
            <i class="bi bi-chat-quote ms-2 text-primary" aria-hidden="true"></i>
            عن المنظمة
        </h2>
    </div>

    <div class="panel-body">
        @if($organization->bio)
            <p class="mb-0">{{ $organization->bio }}</p>
        @else
            <p class="no-data-note mb-0">لم تتم إضافة نبذة عن المنظمة بعد.</p>
        @endif
    </div>
</div>

{{-- Contact info --}}
<div class="panel mb-4">
    <div class="panel-header">
        <h2 class="panel-title">
            <i class="bi bi-telephone ms-2 text-primary" aria-hidden="true"></i>
            معلومات التواصل
        </h2>
    </div>

    <div class="panel-body">
        <div class="row g-3">

            <div class="col-md-4">
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-envelope" aria-hidden="true"></i> البريد الإلكتروني
                    </div>
                    <div class="info-value">{{ $organization->email ?? 'غير متوفر' }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-phone" aria-hidden="true"></i> رقم الهاتف
                    </div>
                    <div class="info-value" dir="ltr" style="text-align:right;">{{ $organization->phone ?? 'غير متوفر' }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-globe2" aria-hidden="true"></i> الموقع الإلكتروني
                    </div>
                    @if($organization->website)
                        <div class="info-value">
                            <a href="{{ $organization->website }}" target="_blank" class="text-decoration-none">{{ $organization->website }}</a>
                        </div>
                    @else
                        <div class="info-value no-data-note">غير متوفر</div>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-geo-alt" aria-hidden="true"></i> العنوان
                    </div>
                    <div class="info-value">{{ $organization->address ?? 'غير متوفر' }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-building" aria-hidden="true"></i> المدينة
                    </div>
                    <div class="info-value">{{ $organization->city ?? 'غير محدد' }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-globe" aria-hidden="true"></i> الدولة
                    </div>
                    <div class="info-value">{{ $organization->country ?? 'غير محددة' }}</div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Opportunities --}}
<div class="panel">
    <div class="panel-header d-flex align-items-center justify-content-between">
        <h2 class="panel-title">
            <i class="bi bi-briefcase ms-2 text-primary" aria-hidden="true"></i>
            فرص المنظمة
        </h2>

        <a href="{{ route('organization.jobs.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
            إضافة فرصة
        </a>
    </div>

    <div class="panel-body">

        @php
            $jobs = $organization->jobs()->withCount('applications')->latest()->limit(5)->get();
        @endphp

        @if($jobs->count())

            <div class="list-unstyled">

                @foreach($jobs as $job)

                    <div class="recent-application">

                        <div class="ra-icon" aria-hidden="true">
                            <i class="bi bi-briefcase"></i>
                        </div>

                        <div class="ra-body">
                            <a href="{{ route('organization.jobs.show', $job) }}"
                               class="ra-title text-decoration-none">
                                {{ $job->title }}
                            </a>

                            <div class="ra-meta">
                                <i class="bi bi-person me-1" aria-hidden="true"></i>
                                {{ $job->applications_count }} متقدم
                            </div>
                        </div>

                        <span class="status-badge status-{{ $job->status }}">
                            @if($job->status === 'published')
                                منشورة
                            @elseif($job->status === 'completed')
                                مكتملة
                            @else
                                ملغاة
                            @endif
                        </span>

                    </div>

                @endforeach

            </div>

        @else

            <div class="text-center py-4">
                <i class="bi bi-briefcase fs-1 text-muted" aria-hidden="true"></i>
                <h5 class="mt-3">لا توجد فرص</h5>
                <p class="text-muted mb-0">لم تقم المنظمة بإنشاء أي فرصة بعد.</p>
            </div>

        @endif

    </div>
</div>

@endsection