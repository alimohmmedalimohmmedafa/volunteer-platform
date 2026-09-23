@extends('layouts.app')

@section('title', $job->title)

@section('content')

{{-- Page --}}
<section class="job-details-section">

    <div class="container">

        {{-- Back --}}
        <div class="mb-4">

            <a href="{{ route('jobs.index') }}"
               class="text-decoration-none fw-semibold">

                <i class="bi bi-arrow-right me-1"></i>
                العودة إلى فرص التطوع

            </a>

        </div>


        @php
            $deadlinePassed = \Carbon\Carbon::parse($job->application_deadline)->isPast();
            $jobOpen = $job->status === 'published' && !$deadlinePassed;
        @endphp


        <div class="row g-4 justify-content-center">

            {{-- Main Content --}}
            <div class="col-lg-8">

                {{-- 1) Job Information --}}
                <div class="job-details-card">

                    <div class="job-details-header">

                        <span class="job-status @if(!$jobOpen) is-closed @endif">

                            @if ($jobOpen)
                                <i class="bi bi-check-circle-fill"></i>
                                الفرصة متاحة للتقديم
                            @else
                                <i class="bi bi-lock-fill"></i>
                                الفرصة مغلقة
                            @endif

                        </span>

                        <i class="bi bi-briefcase job-large-icon" aria-hidden="true"></i>

                    </div>


                    <h1 class="job-details-title">

                        {{ $job->title }}

                    </h1>


                    <div class="d-flex flex-wrap align-items-center gap-4 mt-3">

                        <a
                            href="{{ route('organizations.show', $job->organization) }}"
                            class="job-details-organization text-decoration-none"
                        >

                            <i class="bi bi-building"></i>

                            <span>
                                {{ $job->organization->name }}
                            </span>

                        </a>

                        <span class="job-details-organization m-0">
                            <i class="bi bi-geo-alt"></i>
                            <span>{{ $job->location }}</span>
                        </span>

                    </div>


                    <hr>


                    {{-- About the opportunity --}}
                    <div class="details-block">

                        <h3>
                            <i class="bi bi-file-text"></i>
                            عن الفرصة
                        </h3>

                        <p class="mb-0">
                            {!! nl2br(e($job->description)) !!}
                        </p>

                    </div>


                    {{-- 3) Requirements --}}
                    @if ($job->requirements)

                        <div class="details-block">

                            <h3>
                                <i class="bi bi-list-check"></i>
                                المتطلبات
                            </h3>

                            <p class="mb-0">
                                {!! nl2br(e($job->requirements)) !!}
                            </p>

                        </div>

                    @endif

                </div>


                {{-- 4) Dates --}}
                <div class="org-section-card mt-4">

                    <h3>
                        <i class="bi bi-calendar-range"></i>
                        المواعيد
                    </h3>

                    <div class="job-date-item">
                        <span class="job-date-icon">
                            <i class="bi bi-calendar-event" aria-hidden="true"></i>
                        </span>

                        <div>
                            <small>تاريخ البداية</small>
                            <strong>
                                {{ \Carbon\Carbon::parse($job->start_date)->format('l — d M Y') }}
                            </strong>
                        </div>
                    </div>

                    <div class="job-date-item">
                        <span class="job-date-icon">
                            <i class="bi bi-calendar-check" aria-hidden="true"></i>
                        </span>

                        <div>
                            <small>تاريخ النهاية</small>
                            <strong>
                                {{ \Carbon\Carbon::parse($job->end_date)->format('l — d M Y') }}
                            </strong>
                        </div>
                    </div>

                    <div class="job-date-item @if($deadlinePassed) is-overdue @endif">
                        <span class="job-date-icon">
                            <i class="bi bi-clock" aria-hidden="true"></i>
                        </span>

                        <div>
                            <small>آخر موعد للتقديم</small>
                            <strong>
                                {{ \Carbon\Carbon::parse($job->application_deadline)->format('l — d M Y') }}
                                @if ($deadlinePassed)
                                    (انتهى)
                                @endif
                            </strong>
                        </div>
                    </div>

                </div>

            </div>


            {{-- Sidebar --}}
            <div class="col-lg-4">

                {{-- 2) Organization Information --}}
                <div class="organization-details-card organization-info-card mb-4">
                    <div class="organization-logo-large">

                        @if ($job->organization->logo)

                            <img
                                src="{{ asset('storage/' . $job->organization->logo) }}"
                                alt="شعار {{ $job->organization->name }}">

                        @else

                            <i class="bi bi-building" aria-hidden="true"></i>

                        @endif

                    </div>


                    <h3>
                        {{ $job->organization->name }}
                    </h3>


                    @if ($job->organization->bio)

                        <p>
                            {{ $job->organization->bio }}
                        </p>

                    @endif


                    @if ($job->organization->city)

                        <div class="organization-location">
                            <i class="bi bi-geo-alt"></i>

                            {{ $job->organization->city }}

                            @if ($job->organization->country)
                                ، {{ $job->organization->country }}
                            @endif
                        </div>

                    @endif


                    <a
                        href="{{ route('organizations.show', $job->organization) }}"
                        class="btn btn-outline-primary w-100 mt-4"
                    >
                        <i class="bi bi-building me-1"></i>
                        عرض صفحة المنظمة
                    </a>

                </div>


                {{-- 5) Application --}}
                <div class="apply-card">

                    <h2 class="apply-title">
                        <i class="bi bi-send"></i>
                        التقديم على الفرصة
                    </h2>

                    <p class="apply-note mb-3">
                        {{ $job->applications_count }} متقدم حتى الآن.
                    </p>


                    @if (!$jobOpen)

                        <div class="alert alert-secondary text-center mb-0">

                            <i class="bi bi-lock me-1"></i>

                            الفرصة مغلقة

                        </div>

                    @elseif (!auth()->check())

                        <div class="d-grid">
                            <a href="{{ route('login') }}"
                               class="btn btn-primary btn-lg">

                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                سجل الدخول للتقديم

                            </a>
                        </div>

                        <p class="text-center text-muted small mt-3 mb-0">
                            ليس لديك حساب؟
                            <a href="{{ route('register') }}" class="fw-semibold">
                                أنشئ حسابًا الآن
                            </a>
                        </p>

                    @elseif (auth()->user()->role !== 'volunteer')

                        <div class="alert alert-info mb-0 text-center">
                            <i class="bi bi-info-circle me-1"></i>
                            التقديم متاح للمتطوعين فقط.
                        </div>

                    @else

                        @php
                            $volunteerProfile = auth()->user()->volunteerProfile;
                            $alreadyApplied = $volunteerProfile
                                && $volunteerProfile->applications()
                                    ->where('job_id', $job->id)
                                    ->exists();
                        @endphp

                        @if ($alreadyApplied)

                            <button type="button"
                                    class="btn btn-success btn-lg w-100"
                                    disabled>

                                <i class="bi bi-check2-circle me-2"></i>

                                تم التقديم مسبقًا

                            </button>

                            <p class="text-center text-muted small mt-3 mb-0">
                                يمكنك متابعة حالة طلبك من
                                <a href="{{ route('volunteer.applications.index') }}"
                                   class="fw-semibold">
                                    طلباتي
                                </a>
                            </p>

                        @elseif (!$volunteerProfile || !$volunteerProfile->cv)

                            <div class="alert alert-warning mb-3 text-center">

                                <i class="bi bi-exclamation-triangle me-1"></i>

                                يرجى رفع سيرتك الذاتية قبل التقديم.

                                <a href="{{ route('volunteer.profile.edit') }}"
                                   class="d-block mt-2 fw-semibold">

                                    <i class="bi bi-arrow-left me-1"></i>

                                    رفع السيرة الذاتية

                                </a>

                            </div>

                        @else

                            <form method="POST"
                                  action="{{ route('volunteer.applications.apply', $job) }}">

                                @csrf

                                <div class="d-grid">
                                    <button type="submit"
                                            class="btn btn-primary btn-lg">

                                        <i class="bi bi-send me-2"></i>

                                        قدم الآن

                                    </button>
                                </div>

                            </form>

                        @endif

                    @endif

                </div>

            </div>

        </div>

    </div>

</section>

@endsection