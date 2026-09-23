@extends('layouts.organization')

@section('title', 'ملف المتطوع')

@section('content')

@php
    $volunteer = $application->volunteer;
    $user = $volunteer?->user;
    $skills = $volunteer?->skills
        ? array_filter(array_map('trim', explode(',', $volunteer->skills)), 'strlen')
        : [];
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="fw-bold mb-1">ملف المتطوع</h1>
        <p class="text-muted mb-0">بيانات المتطوع وطلبه على فرصة التطوع.</p>
    </div>

    <a href="{{ route('organization.applications.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-right me-1" aria-hidden="true"></i>
        العودة إلى الطلبات
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="panel mb-4 overflow-hidden">
            <div class="profile-cover"></div>

            <div class="profile-identity">
                @if($volunteer?->photo)
                    <img src="{{ asset('storage/' . $volunteer->photo) }}"
                         alt="الصورة الشخصية لـ {{ $user?->name }}"
                         class="profile-avatar">
                @else
                    <div class="profile-avatar" aria-hidden="true">
                        <i class="bi bi-person"></i>
                    </div>
                @endif

                <div class="flex-grow-1 pb-2">
                    <h2 class="profile-name">{{ $user?->name ?? 'متطوع غير معروف' }}</h2>
                    <div class="profile-location">
                        @if($volunteer?->city || $volunteer?->country)
                            <i class="bi bi-geo-alt me-1" aria-hidden="true"></i>
                            {{ trim(($volunteer?->city ?? '') . ' ' . ($volunteer?->country ?? '')) }}
                        @else
                            <i class="bi bi-envelope me-1" aria-hidden="true"></i>
                            {{ $user?->email ?? 'غير محدد' }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="panel-body pt-0">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-person" aria-hidden="true"></i> الاسم</div>
                            <div class="info-value">{{ $user?->name ?? 'غير محدد' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-envelope" aria-hidden="true"></i> البريد الإلكتروني</div>
                            <div class="info-value">{{ $user?->email ?? 'غير محدد' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-phone" aria-hidden="true"></i> رقم الهاتف</div>
                            <div class="info-value">{{ $volunteer?->phone ?? 'غير محدد' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-geo-alt" aria-hidden="true"></i> الموقع</div>
                            <div class="info-value">
                                {{ trim(($volunteer?->city ?? '') . ' ' . ($volunteer?->country ?? '')) ?: 'غير محدد' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold mb-2">
                        <i class="bi bi-chat-quote ms-2 text-primary" aria-hidden="true"></i>
                        نبذة عن المتطوع
                    </h5>
                    @if($volunteer?->bio)
                        <p class="mb-0">{!! nl2br(e($volunteer->bio)) !!}</p>
                    @else
                        <p class="no-data-note mb-0">لم تتم إضافة نبذة بعد.</p>
                    @endif
                </div>

                <div>
                    <h5 class="fw-bold mb-2">
                        <i class="bi bi-stars ms-2 text-primary" aria-hidden="true"></i>
                        المهارات
                    </h5>
                    @if($skills)
                        <div class="skills-stack">
                            @foreach($skills as $skill)
                                <span class="skill-tag">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="no-data-note mb-0">لم تتم إضافة مهارات بعد.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel mb-4">
            <div class="panel-header">
                <h2 class="panel-title">
                    <i class="bi bi-file-earmark-text ms-2 text-primary" aria-hidden="true"></i>
                    بيانات الطلب
                </h2>
            </div>

            <div class="panel-body">
                <div class="info-item mb-3">
                    <div class="info-label"><i class="bi bi-briefcase" aria-hidden="true"></i> الفرصة</div>
                    <div class="info-value">{{ $application->job->title }}</div>
                </div>

                <div class="info-item mb-3">
                    <div class="info-label"><i class="bi bi-calendar-event" aria-hidden="true"></i> تاريخ التقديم</div>
                    <div class="info-value">{{ $application->applied_at?->format('d M Y') ?? 'غير محدد' }}</div>
                </div>

                <div class="info-item mb-3">
                    <div class="info-label"><i class="bi bi-info-circle" aria-hidden="true"></i> الحالة</div>
                    <div class="info-value">
                        @if($application->status === 'pending') قيد المراجعة
                        @elseif($application->status === 'accepted') مقبول
                        @else مرفوض
                        @endif
                    </div>
                </div>

                @if($application->cv)
                    <a href="{{ route('organization.applications.cv', $application) }}"
                       target="_blank" class="btn btn-outline-primary w-100">
                        <i class="bi bi-file-earmark-pdf me-1" aria-hidden="true"></i>
                        عرض السيرة الذاتية
                    </a>
                @else
                    <p class="text-muted mb-0">لم يرفق المتطوع سيرة ذاتية.</p>
                @endif
            </div>
        </div>

        @if($application->status === 'pending')
            <div class="d-flex flex-wrap gap-2">
                <form method="POST" action="{{ route('organization.applications.accept', $application) }}" class="flex-grow-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-lg me-1" aria-hidden="true"></i>
                        قبول الطلب
                    </button>
                </form>

                <form method="POST" action="{{ route('organization.applications.reject', $application) }}" class="flex-grow-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-lg me-1" aria-hidden="true"></i>
                        رفض الطلب
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

@endsection
