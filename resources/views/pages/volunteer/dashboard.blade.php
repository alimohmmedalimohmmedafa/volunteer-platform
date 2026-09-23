@extends('layouts.volunteer')

@section('title', 'لوحة التحكم')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h1 class="fw-bold mb-1">مرحبًا، {{ $user->name }} 👋</h1>
        <p class="text-muted mb-0">هذه نظرة سريعة على نشاطك التطوعي.</p>
    </div>

    <a href="{{ route('jobs.index') }}" class="btn btn-primary">
        <i class="bi bi-search me-1" aria-hidden="true"></i>
        اكتشف فرص التطوع
    </a>
</div>

{{-- Stats cards --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <div class="card stat-card stat-total h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" aria-hidden="true">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $applicationsCount }}</div>
                    <div class="stat-label">إجمالي الطلبات</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card stat-pending h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" aria-hidden="true">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $pendingCount }}</div>
                    <div class="stat-label">قيد الانتظار</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card stat-accepted h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" aria-hidden="true">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $acceptedCount }}</div>
                    <div class="stat-label">مقبولة</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card stat-rejected h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" aria-hidden="true">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $rejectedCount }}</div>
                    <div class="stat-label">مرفوضة</div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row g-4">

    {{-- Recent applications --}}
    <div class="col-lg-7">

        <div class="panel h-100">
            <div class="panel-header d-flex align-items-center justify-content-between">
                <h2 class="panel-title">
                    <i class="bi bi-clock-history ms-2 text-primary" aria-hidden="true"></i>
                    أحدث الطلبات
                </h2>

                <a href="{{ route('volunteer.applications.index') }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>

            <div class="panel-body">

                @php
                    $recentApplications = auth()->user()->volunteerProfile
                        ? auth()->user()->volunteerProfile->applications()
                            ->with('job.organization')
                            ->orderByDesc('applied_at')
                            ->limit(5)
                            ->get()
                        : collect();
                @endphp

                @if($recentApplications->count())

                    <div class="list-unstyled">

                        @foreach($recentApplications as $application)

                            <div class="recent-application">

                                <div class="ra-icon" aria-hidden="true">
                                    <i class="bi bi-briefcase"></i>
                                </div>

                                <div class="ra-body">
                                    <a href="{{ route('jobs.show', $application->job) }}"
                                       class="ra-title text-decoration-none">
                                        {{ $application->job->title }}
                                    </a>

                                    <div class="ra-meta">
                                        <i class="bi bi-building me-1" aria-hidden="true"></i>
                                        {{ $application->job->organization->name }}
                                        <span class="mx-1">•</span>
                                        <i class="bi bi-calendar-event me-1" aria-hidden="true"></i>
                                        {{ $application->applied_at?->format('d M Y') }}
                                    </div>
                                </div>

                                <span class="status-badge status-{{ $application->status }}">
                                    @if($application->status === 'pending')
                                        <i class="bi bi-hourglass-split" aria-hidden="true"></i> قيد المراجعة
                                    @elseif($application->status === 'accepted')
                                        <i class="bi bi-check-circle" aria-hidden="true"></i> مقبول
                                    @else
                                        <i class="bi bi-x-circle" aria-hidden="true"></i> مرفوض
                                    @endif
                                </span>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted" aria-hidden="true"></i>
                        <h5 class="mt-3">لا توجد طلبات بعد</h5>
                        <p class="text-muted mb-3">ابدأ رحلتك بالتقديم على أول فرصة تطوع.</p>
                        <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                            <i class="bi bi-briefcase me-1" aria-hidden="true"></i>
                            تصفح فرص التطوع
                        </a>
                    </div>

                @endif

            </div>
        </div>

    </div>

    {{-- Profile completion --}}
    <div class="col-lg-5">

        <div class="panel h-100">
            <div class="panel-header">
                <h2 class="panel-title">
                    <i class="bi bi-person-check ms-2 text-primary" aria-hidden="true"></i>
                    اكتمال الملف الشخصي
                </h2>
            </div>

            <div class="panel-body d-flex flex-column align-items-center text-center">

                @php
                    $checks = [
                        'bio'     => !empty($profile?->bio),
                        'skills'  => !empty($profile?->skills),
                        'cv'      => !empty($profile?->cv),
                        'photo'   => !empty($profile?->photo),
                        'contact' => !empty($profile?->phone) || !empty($profile?->city) || !empty($profile?->country),
                    ];
                    $pct = count(array_filter($checks)) * 20;
                @endphp

                <div class="completion-ring mb-3" role="img" aria-label="اكتمال الملف {{ $pct }}%">
                    <svg width="112" height="112" viewBox="0 0 112 112">
                        <circle class="ring-track"
                            cx="56" cy="56" r="50" fill="none" stroke-width="10"></circle>
                        <circle class="ring-value"
                            cx="56" cy="56" r="50" fill="none" stroke-width="10"
                            stroke-dasharray="314.16"
                            stroke-dashoffset="{{ 314.16 * (1 - $pct / 100) }}"></circle>
                    </svg>
                    <span class="ring-text">{{ $pct }}%</span>
                </div>

                @if($pct < 100)

                    <p class="text-muted mb-3">أكمل ملفك لزيادة فرص قبول طلباتك.</p>

                    <div class="completion-list w-100 text-start">

                        @foreach([
                            ['bio', 'أضف نبذة عنك', route('volunteer.profile.edit')],
                            ['skills', 'أضف مهاراتك', route('volunteer.profile.edit')],
                            ['cv', 'ارفع سيرتك الذاتية', route('volunteer.profile.edit')],
                            ['photo', 'أضف صورتك الشخصية', route('volunteer.profile.edit')],
                            ['contact', 'أضف بيانات التواصل', route('volunteer.profile.edit')],
                        ] as [$key, $label, $url])

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox"
                                       id="check-{{ $key }}"
                                       {{ $checks[$key] ? 'checked disabled' : '' }}>
                                <label class="form-check-label" for="check-{{ $key }}">
                                    @if($checks[$key])
                                        <span class="text-success">{{ $label }}</span>
                                    @else
                                        <a href="{{ $url }}" class="text-decoration-none">{{ $label }}</a>
                                    @endif
                                </label>
                            </div>

                        @endforeach

                    </div>

                @else

                    <p class="text-success fw-semibold mb-0">
                        <i class="bi bi-patch-check-fill me-1" aria-hidden="true"></i>
                        ملفك مكتمل — رائع!
                    </p>

                @endif

            </div>
        </div>

    </div>

</div>

{{-- Notifications summary --}}
@if($recentNotifications->count())

    <div class="panel mt-4">
        <div class="panel-header d-flex align-items-center justify-content-between">
            <h2 class="panel-title">
                <i class="bi bi-bell ms-2 text-primary" aria-hidden="true"></i>
                إشعاراتي
                @if($unreadNotificationsCount > 0)
                    <span class="badge bg-danger rounded-pill ms-1">
                        {{ $unreadNotificationsCount }} غير مقروء
                    </span>
                @endif
            </h2>

            <a href="{{ route('volunteer.notifications.index') }}" class="btn btn-sm btn-outline-primary">
                عرض الكل
            </a>
        </div>

        <div class="panel-body">

            <div class="row g-3">

                @foreach($recentNotifications as $notification)

                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi {{ $notification->is_read ? 'bi-envelope' : 'bi-envelope-fill text-primary' }}" aria-hidden="true"></i>
                                {{ $notification->created_at?->diffForHumans() }}
                            </div>
                            <div class="info-value">{{ $notification->title }}</div>
                            <div class="text-muted small mt-1">{{ $notification->message }}</div>
                        </div>
                    </div>

                @endforeach

            </div>

        </div>
    </div>

@endif

@endsection