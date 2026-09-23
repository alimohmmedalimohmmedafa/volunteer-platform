@extends('layouts.volunteer')

@section('title', 'طلباتي')

@section('content')

<div class="applications-toolbar">
    <div>
        <h1 class="fw-bold mb-1">طلباتي</h1>
        <p class="text-muted mb-0">جميع الطلبات التي قدمتها على فرص التطوع.</p>
    </div>

    <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-briefcase me-1" aria-hidden="true"></i>
        تصفح فرص جديدة
    </a>
</div>

@if($applications->count())

    <div class="panel">
        <div class="panel-body p-0">

            <ul class="list-unstyled mb-0">

                @foreach($applications as $application)

                    <li class="application-row p-3 p-lg-4">

                        <div class="ar-icon mx-auto mx-lg-0 mb-2 mb-lg-0" aria-hidden="true">
                            <i class="bi bi-briefcase"></i>
                        </div>

                        <div class="text-center text-lg-start">
                            <a href="{{ route('jobs.show', $application->job) }}"
                               class="ar-title text-decoration-none d-block">
                                {{ $application->job->title }}
                            </a>

                            <div class="ar-meta justify-content-center justify-content-lg-start mt-1">
                                <span>
                                    <i class="bi bi-building me-1" aria-hidden="true"></i>
                                    {{ $application->job->organization->name }}
                                </span>
                                <span>
                                    <i class="bi bi-calendar-event me-1" aria-hidden="true"></i>
                                    تقدّم في: {{ $application->applied_at?->format('d M Y') }}
                                </span>
                                @if($application->job->location)
                                    <span>
                                        <i class="bi bi-geo-alt me-1" aria-hidden="true"></i>
                                        {{ $application->job->location }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-end gap-2">
                            <span class="status-badge status-{{ $application->status }}">
                                @if($application->status === 'pending')
                                    <i class="bi bi-hourglass-split" aria-hidden="true"></i> قيد المراجعة
                                @elseif($application->status === 'accepted')
                                    <i class="bi bi-check-circle" aria-hidden="true"></i> مقبول
                                @else
                                    <i class="bi bi-x-circle" aria-hidden="true"></i> مرفوض
                                @endif
                            </span>

                            @if($application->cv)
                                <a href="{{ route('volunteer.applications.cv', $application) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary text-nowrap">
                                    <i class="bi bi-file-earmark-pdf me-1" aria-hidden="true"></i>
                                    السيرة الذاتية
                                </a>
                            @endif
                        </div>

                    </li>

                @endforeach

            </ul>

        </div>
    </div>

    <div class="mt-4">
        {{ $applications->links() }}
    </div>

@else

    <div class="panel">
        <div class="panel-body text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted" aria-hidden="true"></i>
            <h4 class="mt-3">لا توجد طلبات</h4>
            <p class="text-muted mb-3">
                لم تقدم على أي فرصة تطوع بعد. تصفح الفرص المتاحة وابدأ رحلتك.
            </p>
            <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                <i class="bi bi-briefcase me-1" aria-hidden="true"></i>
                تصفح فرص التطوع
            </a>
        </div>
    </div>

@endif

@endsection