@extends('layouts.app')

@section('title', $organization->name)

@section('content')

<section class="job-details-section">

    <div class="container">

        {{-- العودة إلى المنظمات --}}
        <div class="mb-4">
            <a
                href="{{ route('organizations.index') }}"
                class="text-decoration-none fw-semibold"
            >
                <i class="bi bi-arrow-right me-1"></i>
                العودة إلى المنظمات
            </a>
        </div>


        {{-- 1) Organization Header --}}
        <div class="org-profile mb-5">

            <div class="org-profile-cover"></div>

            <div class="org-profile-body">

                <div class="org-profile-identity">

                    <div class="org-profile-logo">

                        @if($organization->logo)

                            <img
                                src="{{ asset('storage/' . $organization->logo) }}"
                                alt="شعار {{ $organization->name }}">

                        @else

                            <i class="bi bi-building" aria-hidden="true"></i>

                        @endif

                    </div>

                    <div class="org-profile-meta">

                        <span class="badge text-bg-success mb-2">
                            <i class="bi bi-patch-check-fill me-1"></i>
                            منظمة معتمدة
                        </span>

                        <h1>
                            {{ $organization->name }}
                        </h1>

                    </div>

                </div>


                <div class="org-profile-stats">

                    @if($organization->city)

                        <span class="stat">
                            <i class="bi bi-geo-alt" aria-hidden="true"></i>
                            {{ $organization->city }}
                            @if($organization->country)
                                ، {{ $organization->country }}
                            @endif
                        </span>

                    @endif

                    <span class="stat">
                        <i class="bi bi-briefcase" aria-hidden="true"></i>
                        {{ $organization->jobs_count }}
                        {{ $organization->jobs_count == 1 ? 'فرصة منشورة' : 'فرصة منشورة' }}
                    </span>

                </div>

            </div>

        </div>


        <div class="row g-4">

            {{-- Main column --}}
            <div class="col-lg-8">

                {{-- 2) About the organization --}}
                <div class="org-section-card mb-4">

                    <h3>
                        <i class="bi bi-info-circle"></i>
                        عن المنظمة
                    </h3>

                    <p class="mb-0 text-secondary lh-lg">
                        {{ $organization->bio ?? 'لا توجد نبذة عن هذه المنظمة بعد.' }}
                    </p>

                </div>


                {{-- 4) Volunteer Opportunities --}}
                <div>

                    <div class="org-opportunity-title">

                        <div>
                            <h2>
                                فرص التطوع
                            </h2>

                            <p>
                                فرص التطوع المنشورة من {{ $organization->name }}.
                            </p>
                        </div>

                        <span class="badge badge-soft">
                            {{ $organization->jobs_count }}
                            {{ $organization->jobs_count == 1 ? 'فرصة' : 'فرصة' }}
                        </span>

                    </div>


                    @if($organization->jobs->count())

                        <div class="row g-4">

                            @foreach($organization->jobs as $job)

                                @php
                                    $jobOpen = $job->status === 'published'
                                        && !\Carbon\Carbon::parse($job->application_deadline)->isPast();
                                @endphp

                                <div class="col-md-6">

                                    <article class="job-card h-100">

                                        <div class="job-card-top">

                                            <span class="job-status @if(!$jobOpen) is-closed @endif">

                                                @if ($jobOpen)
                                                    <i class="bi bi-check-circle-fill"></i>
                                                    متاحة
                                                @else
                                                    <i class="bi bi-lock-fill"></i>
                                                    مغلقة
                                                @endif

                                            </span>

                                            <i class="bi bi-briefcase job-card-icon" aria-hidden="true"></i>

                                        </div>


                                        <h3 class="job-title">
                                            {{ $job->title }}
                                        </h3>


                                        <p class="job-description">
                                            {{ \Illuminate\Support\Str::limit($job->description, 90) }}
                                        </p>


                                        <div class="job-details mb-3">

                                            <div>
                                                <i class="bi bi-geo-alt"></i>
                                                <span>{{ $job->location }}</span>
                                            </div>

                                            <div>
                                                <i class="bi bi-clock"></i>
                                                <span>
                                                    آخر موعد:
                                                    {{ \Carbon\Carbon::parse($job->application_deadline)->format('d M Y') }}
                                                </span>
                                            </div>

                                        </div>


                                        <a
                                            href="{{ route('jobs.show', $job) }}"
                                            class="btn btn-outline-primary w-100 mt-auto"
                                        >
                                            <i class="bi bi-eye me-1"></i>
                                            عرض التفاصيل
                                        </a>

                                    </article>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="empty-state">

                            <div class="empty-state-icon">
                                <i class="bi bi-briefcase"></i>
                            </div>

                            <h3>
                                لا توجد فرص تطوعية حاليًا
                            </h3>

                            <p class="mb-0">
                                لا تملك هذه المنظمة حاليًا فرص تطوع منشورة.
                            </p>

                        </div>

                    @endif

                </div>

            </div>


            {{-- Sidebar: Contact information --}}
            <div class="col-lg-4">

                <div class="org-section-card mb-4">

                    <h3>
                        <i class="bi bi-telephone"></i>
                        معلومات التواصل
                    </h3>

                    @if($organization->phone)

                        <div class="org-contact-item">
                            <i class="bi bi-telephone"></i>

                            <div>
                                <small>رقم الهاتف</small>
                                <span dir="ltr">{{ $organization->phone }}</span>
                            </div>
                        </div>

                    @endif


                    @if($organization->email)

                        <div class="org-contact-item">
                            <i class="bi bi-envelope"></i>

                            <div>
                                <small>البريد الإلكتروني</small>
                                <span dir="ltr">{{ $organization->email }}</span>
                            </div>
                        </div>

                    @endif


                    @if($organization->website)

                        <div class="org-contact-item">
                            <i class="bi bi-globe"></i>

                            <div>
                                <small>الموقع الإلكتروني</small>

                                <a
                                    href="{{ $organization->website }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    زيارة الموقع
                                </a>
                            </div>
                        </div>

                    @endif


                    @if($organization->address)

                        <div class="org-contact-item">
                            <i class="bi bi-geo"></i>

                            <div>
                                <small>العنوان</small>
                                <span>{{ $organization->address }}</span>
                            </div>
                        </div>

                    @endif


                    @if(!$organization->phone && !$organization->email
                        && !$organization->website && !$organization->address)

                        <p class="text-muted mb-0">
                            لا تتوفر معلومات تواصل إضافية لهذه المنظمة.
                        </p>

                    @endif

                </div>

            </div>

        </div>

    </div>

</section>

@endsection