@php
    $unreadApplications = auth()->user()->organization
        ? \App\Models\Application::whereHas('job', fn ($q) => $q->where('organization_id', auth()->user()->organization->id))
            ->where('status', 'pending')
            ->count()
        : 0;
@endphp

<div class="d-flex flex-column h-100">

    <div class="mb-4 sidebar-brand">
        <h5 class="fw-bold brand">
            <i class="bi bi-heart-fill text-primary"></i>
            منصة التطوع
        </h5>
        <small>
            لوحة المنظمة
        </small>
    </div>

    <nav class="nav flex-column flex-grow-1">

        <a href="{{ route('organization.dashboard') }}"
           class="nav-link {{ request()->routeIs('organization.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 ms-2"></i>
            لوحة التحكم
        </a>

        <a href="{{ route('organization.jobs.index') }}"
           class="nav-link {{ request()->routeIs('organization.jobs.index') ? 'active' : '' }}">
            <i class="bi bi-briefcase ms-2"></i>
            فرص التطوع
        </a>

        <a href="{{ route('organization.jobs.create') }}"
           class="nav-link {{ request()->routeIs('organization.jobs.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle ms-2"></i>
            إضافة فرصة
        </a>

        <a href="{{ route('organization.applications.index') }}"
           class="nav-link {{ request()->routeIs('organization.applications*') ? 'active' : '' }}">
            <i class="bi bi-people ms-2"></i>
            طلبات المتطوعين
            @if($unreadApplications > 0)
                <span class="badge bg-warning rounded-pill text-dark ms-1">
                    {{ $unreadApplications }}
                </span>
            @endif
        </a>

        <a href="{{ route('organization.profile') }}"
           class="nav-link {{ request()->routeIs('organization.profile*') ? 'active' : '' }}">
            <i class="bi bi-building ms-2"></i>
            ملف المنظمة
        </a>

        <hr class="border-secondary my-3">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                    class="nav-link logout border-0 bg-transparent w-100 text-end p-0">
                <i class="bi bi-box-arrow-right ms-2"></i>
                تسجيل الخروج
            </button>
        </form>

    </nav>

</div>