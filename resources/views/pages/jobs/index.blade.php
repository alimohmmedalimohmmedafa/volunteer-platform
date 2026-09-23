@extends('layouts.app')

@section('title', 'فرص التطوع')

@section('content')

{{-- Page Header --}}
<section class="jobs-header">

    <div class="container">

        <div class="text-center">

            <span class="jobs-header-badge">
                <i class="bi bi-search"></i>
                اكتشف الفرص
            </span>

            <h1>
                فرص التطوع
            </h1>

            <p>
                ابحث عن فرصة تطوعية تناسب مهاراتك واهتماماتك.
            </p>

        </div>

    </div>

</section>


{{-- Search & Filters --}}
<section class="jobs-search-section">

    <div class="container">

        <form method="GET"
              action="{{ route('jobs.index') }}">

            <div class="row g-3">

                {{-- Search --}}
                <div class="col-lg-5">

                    <label class="form-label">
                        البحث
                    </label>

                    <div class="input-group">

                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="ابحث عن فرصة..."
                            value="{{ request('search') }}"
                        >

                    </div>

                </div>


                {{-- Location --}}
                <div class="col-lg-3">

                    <label class="form-label">
                        الموقع
                    </label>

                    <input
                        type="text"
                        name="location"
                        class="form-control"
                        placeholder="مثال: الخرطوم"
                        value="{{ request('location') }}"
                    >

                </div>


                {{-- Organization --}}
                <div class="col-lg-2">

                    <label class="form-label">
                        المنظمة
                    </label>

                    <select
                        name="organization_id"
                        class="form-select">

                        <option value="">
                            كل المنظمات
                        </option>

                        @foreach ($organizations as $organization)

                            <option
                                value="{{ $organization->id }}"
                                @selected(request('organization_id') == $organization->id)
                            >
                                {{ $organization->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Deadline --}}
                <div class="col-lg-2">

                    <label class="form-label">
                        موعد التقديم
                    </label>

                    <select
                        name="deadline"
                        class="form-select">

                        <option value="">
                            الكل
                        </option>

                        <option value="open"
                            @selected(request('deadline') === 'open')>
                            مفتوح
                        </option>

                        <option value="closed"
                            @selected(request('deadline') === 'closed')>
                            مغلق
                        </option>

                    </select>

                </div>

            </div>


            <div class="d-flex gap-2 mt-3">

                <button type="submit"
                        class="btn btn-primary px-4">

                    <i class="bi bi-search me-1"></i>
                    بحث

                </button>

                <a href="{{ route('jobs.index') }}"
                   class="btn btn-outline-secondary">

                    إعادة تعيين

                </a>

            </div>

        </form>

    </div>

</section>


{{-- Jobs --}}
<section class="jobs-section">

    <div class="container">

        @if ($jobs->count())

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <h2 class="h4 fw-bold mb-1">
                        فرص التطوع المتاحة
                    </h2>

                    <p class="text-muted mb-0">
                        {{ $jobs->total() }} فرصة متاحة
                    </p>
                </div>

            </div>


            <div class="row g-4">

                @foreach ($jobs as $job)

                    @php
                        $jobOpen = $job->status === 'published'
                            && !\Carbon\Carbon::parse($job->application_deadline)->isPast();
                        $closeToDeadline = $jobOpen
                            && \Carbon\Carbon::parse($job->application_deadline)->diffInDays(now()) <= 3;
                    @endphp

                    <div class="col-md-6 col-lg-4">

                        <article class="job-card h-100">

                            <div class="job-card-top">

                                <div class="job-card-logo">

                                    @if ($job->organization->logo)

                                        <img
                                            src="{{ asset('storage/' . $job->organization->logo) }}"
                                            alt="{{ $job->organization->name }}">

                                    @else

                                        <i class="bi bi-building" aria-hidden="true"></i>

                                    @endif

                                </div>

                                <span class="job-status @if(!$jobOpen) is-closed @endif">

                                    @if ($jobOpen)
                                        <i class="bi bi-check-circle-fill"></i>
                                        متاحة
                                    @else
                                        <i class="bi bi-lock-fill"></i>
                                        مغلقة
                                    @endif

                                </span>

                            </div>


                            <h3 class="job-title">
                                {{ $job->title }}
                            </h3>


                            <div class="job-organization">

                                <i class="bi bi-building"></i>

                                <span>
                                    {{ $job->organization->name }}
                                </span>

                            </div>


                            <p class="job-description">

                                {{ \Illuminate\Support\Str::limit($job->description, 120) }}

                            </p>


                            <div class="job-details">

                                <div>
                                    <i class="bi bi-geo-alt"></i>

                                    <span>
                                        {{ $job->location }}
                                    </span>
                                </div>

                                <div>
                                    <i class="bi bi-calendar-event"></i>

                                    <span>
                                        {{ \Carbon\Carbon::parse($job->start_date)->format('d M Y') }}
                                    </span>
                                </div>

                                <div>
                                    <i class="bi bi-calendar-check"></i>

                                    <span>
                                        {{ \Carbon\Carbon::parse($job->end_date)->format('d M Y') }}
                                    </span>
                                </div>

                                <div class="job-deadline @if($closeToDeadline) is-urgent @endif">
                                    <i class="bi bi-clock"></i>

                                    <span>
                                        آخر موعد:
                                        {{ \Carbon\Carbon::parse($job->application_deadline)->format('d M Y') }}
                                    </span>
                                </div>

                                <div>
                                    <i class="bi bi-people"></i>

                                    <span>
                                        {{ $job->applications_count }} متقدم
                                    </span>
                                </div>

                            </div>


                            <a href="{{ route('jobs.show', $job) }}"
                               class="btn btn-outline-primary mt-auto">

                                عرض التفاصيل

                                <i class="bi bi-arrow-left ms-2"></i>

                            </a>

                        </article>

                    </div>

                @endforeach

            </div>


            {{-- Pagination --}}
            @if ($jobs->hasPages())

                <div class="d-flex justify-content-center mt-5">

                    {{ $jobs->links() }}

                </div>

            @endif


        @else

            {{-- Empty State --}}
            <div class="empty-state">

                <div class="empty-state-icon">
                    <i class="bi bi-search"></i>
                </div>

                <h3>
                    لا توجد فرص تطوع
                </h3>

                <p>
                    لم نجد فرصًا تطوعية مطابقة لمعايير البحث الحالية.
                </p>

                <a href="{{ route('jobs.index') }}"
                   class="btn btn-primary">

                    عرض جميع الفرص

                </a>

            </div>

        @endif

    </div>

</section>

@endsection
