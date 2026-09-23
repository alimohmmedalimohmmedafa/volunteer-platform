@extends('layouts.volunteer')

@section('title', 'ملفي الشخصي')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="fw-bold mb-1">ملفي الشخصي</h1>
        <p class="text-muted mb-0">معلوماتك الشخصية ومهاراتك وسيرتك الذاتية.</p>
    </div>

    <a href="{{ route('volunteer.profile.edit') }}" class="btn btn-primary">
        <i class="bi bi-pencil me-1" aria-hidden="true"></i>
        تعديل الملف
    </a>
</div>

<div class="panel overflow-hidden">

    <div class="profile-cover"></div>

    <div class="profile-identity">
        @if($profile?->photo)
            <img src="{{ asset('storage/' . $profile->photo) }}"
                 alt="الصورة الشخصية لـ {{ $user->name }}"
                 class="profile-avatar">
        @else
            <div class="profile-avatar" aria-hidden="true">
                <i class="bi bi-person"></i>
            </div>
        @endif

        <div class="flex-grow-1 pb-2">
            <h2 class="profile-name">{{ $user->name }}</h2>

            <div class="profile-location">
                @if($profile?->city || $profile?->country)
                    <i class="bi bi-geo-alt me-1" aria-hidden="true"></i>
                    {{ trim(($profile?->city ?? '') . ' ' . ($profile?->country ?? '')) }}
                @else
                    <i class="bi bi-envelope me-1" aria-hidden="true"></i>
                    {{ $user->email }}
                @endif
            </div>
        </div>
    </div>

    <div class="panel-body pt-0">

        {{-- Personal info grid --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-person" aria-hidden="true"></i> الاسم
                    </div>
                    <div class="info-value">{{ $user->name }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-envelope" aria-hidden="true"></i> البريد الإلكتروني
                    </div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-phone" aria-hidden="true"></i> رقم الهاتف
                    </div>
                    <div class="info-value">{{ $profile?->phone ?? 'غير محدد' }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-geo-alt" aria-hidden="true"></i> المدينة
                    </div>
                    <div class="info-value">{{ $profile?->city ?? 'غير محدد' }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-globe2" aria-hidden="true"></i> الدولة
                    </div>
                    <div class="info-value">{{ $profile?->country ?? 'غير محدد' }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-item d-flex flex-column justify-content-center">
                    <div class="info-label">
                        <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i> السيرة الذاتية
                    </div>
                    @if($profile?->cv)
                        <div class="info-value d-flex flex-wrap gap-2 mt-1">
                            <a href="{{ asset('storage/' . $profile->cv) }}" target="_blank"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1" aria-hidden="true"></i> عرض
                            </a>
                            <a href="{{ asset('storage/' . $profile->cv) }}" download
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-download me-1" aria-hidden="true"></i> تحميل
                            </a>
                        </div>
                    @else
                        <div class="info-value no-data-note">لم تتم إضافة سيرة ذاتية</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Bio --}}
        <div class="mb-4">
            <h5 class="fw-bold mb-2">
                <i class="bi bi-chat-quote ms-2 text-primary" aria-hidden="true"></i>
                نبذة عني
            </h5>

            @if($profile?->bio)
                <p class="mb-0">{{ $profile->bio }}</p>
            @else
                <p class="no-data-note mb-0">لم تتم إضافة نبذة بعد.</p>
            @endif
        </div>

        {{-- Skills --}}
        <div>
            <h5 class="fw-bold mb-2">
                <i class="bi bi-stars ms-2 text-primary" aria-hidden="true"></i>
                المهارات
            </h5>

            @if($profile?->skills)
                @php
                    $skillsList = array_filter(array_map('trim', explode(',', $profile->skills)), 'strlen');
                @endphp

                <div class="skills-stack">
                    @foreach($skillsList as $skill)
                        <span class="skill-tag">{{ $skill }}</span>
                    @endforeach
                </div>
            @else
                <p class="no-data-note mb-0">لم تتم إضافة مهارات بعد.</p>
            @endif
        </div>

    </div>

</div>

@endsection