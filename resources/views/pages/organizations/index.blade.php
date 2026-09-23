@extends('layouts.app')

@section('title', 'المنظمات')

@section('content')

<div class="bg-light py-5">
    <div class="container">

    {{-- عنوان الصفحة --}}
    <div class="text-center mb-5">
        <span class="badge text-bg-primary mb-3">
            <i class="bi bi-building me-1"></i>
            المنظمات
        </span>

        <h1 class="fw-bold mb-3">
            اكتشف المنظمات
        </h1>

        <p class="text-muted mb-0">
            تعرّف على المنظمات المعتمدة واكتشف فرص التطوع المتاحة لديها.
        </p>
    </div>

    {{-- البحث --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">

            <form method="GET" action="{{ route('organizations.index') }}">

                <div class="row g-3">

                    <div class="col-md-10">
                        <label for="search" class="form-label fw-semibold">
                            البحث عن منظمة
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="ابحث باسم المنظمة أو نبذة عنها أو المدينة..."
                            >
                        </div>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>
                            بحث
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- قائمة المنظمات --}}
    @if($organizations->count())

        <div class="row g-4">

            @foreach($organizations as $organization)

                <div class="col-md-6 col-lg-4">

                    <article class="organization-card h-100">

                        @if($organization->logo)

                            <img
                                src="{{ asset('storage/' . $organization->logo) }}"
                                alt="شعار {{ $organization->name }}"
                                class="organization-logo"
                            >

                        @else

                            <div class="organization-logo-placeholder">
                                <i class="bi bi-building" aria-hidden="true"></i>
                            </div>

                        @endif


                        <h4 class="mb-2">
                            {{ $organization->name }}
                        </h4>


                        <p class="mb-3">
                            {{ Str::limit($organization->bio ?? 'لا توجد نبذة عن المنظمة.', 120) }}
                        </p>


                        <div class="organization-location mb-2">
                            <i class="bi bi-geo-alt"></i>

                            <span>
                                {{ $organization->city ?? 'غير محدد' }}
                                @if($organization->country)
                                    ، {{ $organization->country }}
                                @endif
                            </span>
                        </div>


                        <span class="badge badge-soft align-self-start mb-4">
                            <i class="bi bi-briefcase me-1"></i>
                            {{ $organization->jobs_count }}
                            {{ $organization->jobs_count == 1 ? 'فرصة تطوعية' : 'فرص تطوعية' }}
                        </span>


                        <a
                            href="{{ route('organizations.show', $organization) }}"
                            class="btn btn-outline-primary w-100 mt-auto"
                        >
                            <i class="bi bi-building me-1"></i>
                            عرض المنظمة
                        </a>

                    </article>

                </div>

            @endforeach

        </div>

        {{-- Pagination --}}
        @if($organizations->hasPages())

            <div class="d-flex justify-content-center mt-5">
                {{ $organizations->links() }}
            </div>

        @endif

    @else

        {{-- حالة عدم وجود نتائج --}}
        <div class="empty-state">

            <div class="empty-state-icon">
                <i class="bi bi-building"></i>
            </div>

            <h3>
                لم يتم العثور على منظمات
            </h3>

            <p class="mb-4">
                لم نجد أي منظمة مطابقة لبحثك. يمكنك تصفّح جميع الفرص المتاحة.
            </p>

            <a href="{{ route('organizations.index') }}"
               class="btn btn-primary">
                عرض جميع المنظمات
            </a>

        </div>

    @endif

</div>

</div>

@endsection
