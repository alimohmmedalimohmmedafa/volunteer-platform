@extends('layouts.organization')

@section('title', 'لوحة التحكم')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h1 class="fw-bold mb-1">مرحبًا، {{ $organization->name }}</h1>
        <p class="text-muted mb-0">نظرة عامة على نشاط منظمتك التطوعي.</p>
    </div>

    <a href="{{ route('organization.jobs.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
        إضافة فرصة تطوع
    </a>
</div>

{{-- Stats cards --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <div class="card stat-card stat-jobs h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" aria-hidden="true">
                    <i class="bi bi-briefcase"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $publishedJobs + $completedJobs + $cancelledJobs }}</div>
                    <div class="stat-label">إجمالي الفرص</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card stat-published h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" aria-hidden="true">
                    <i class="bi bi-megaphone"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $publishedJobs }}</div>
                    <div class="stat-label">منشورة</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card stat-completed h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" aria-hidden="true">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $completedJobs }}</div>
                    <div class="stat-label">مكتملة</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card stat-applicants h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" aria-hidden="true">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $totalApplicants }}</div>
                    <div class="stat-label">إجمالي المتقدمين</div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row g-4">

    {{-- Recent activity: latest applications --}}
    <div class="col-lg-7">

        <div class="panel h-100">
            <div class="panel-header d-flex align-items-center justify-content-between">
                <h2 class="panel-title">
                    <i class="bi bi-clock-history ms-2 text-primary" aria-hidden="true"></i>
                    أحدث الطلبات
                </h2>

                <a href="{{ route('organization.applications.index') }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>

            <div class="panel-body">

                @php
                    $recentApplications = $organization->jobs()->withCount('applications')
                        ->get()
                        ->flatMap(fn ($job) => $job->applications()->with('volunteer.user', 'job')->latest('applied_at')->limit(3)->get())
                        ->sortByDesc('applied_at')
                        ->take(5);
                @endphp

                @if($recentApplications->count())

                    <div class="list-unstyled">

                        @foreach($recentApplications as $application)

                            <div class="recent-application">

                                <div class="ra-icon" aria-hidden="true">
                                    <i class="bi bi-person"></i>
                                </div>

                                <div class="ra-body">
                                    <div class="ra-title">
                                        {{ $application->volunteer?->user?->name ?? 'متطوع' }}
                                    </div>

                                    <div class="ra-meta">
                                        <i class="bi bi-briefcase me-1" aria-hidden="true"></i>
                                        {{ $application->job->title }}
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
                        <p class="text-muted mb-3">لم يصلك أي طلب على فرص منظمتك.</p>
                        <a href="{{ route('organization.jobs.index') }}" class="btn btn-primary">
                            <i class="bi bi-briefcase me-1" aria-hidden="true"></i>
                            عرض الفرص
                        </a>
                    </div>

                @endif

            </div>
        </div>

    </div>

    {{-- Recent jobs + quick actions --}}
    <div class="col-lg-5">

        <div class="panel h-100">
            <div class="panel-header d-flex align-items-center justify-content-between">
                <h2 class="panel-title">
                    <i class="bi bi-briefcase ms-2 text-primary" aria-hidden="true"></i>
                    أحدث الفرص
                </h2>

                <a href="{{ route('organization.jobs.index') }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>

            <div class="panel-body">

                @php
                    $recentJobs = $organization->jobs()->withCount('applications')
                        ->latest()
                        ->limit(4)
                        ->get();
                @endphp

                @if($recentJobs->count())

                    <div class="list-unstyled">

                        @foreach($recentJobs as $job)

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
                        <p class="text-muted mb-3">ابدأ بإنشاء أول فرصة تطوع.</p>
                        <a href="{{ route('organization.jobs.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
                            إضافة فرصة
                        </a>
                    </div>

                @endif

            </div>
        </div>

    </div>

</div>

@endsection