@php
    $unreadNotifications = auth()->user()->notifications()
        ->where('is_read', false)
        ->count();
@endphp

<div class="d-flex flex-column h-100">

    <div class="mb-4 sidebar-brand">
        <h5 class="fw-bold brand">
            <i class="bi bi-heart-fill text-primary"></i>
            منصة التطوع
        </h5>
        <small>
            لوحة المتطوع
        </small>
    </div>

    <nav class="nav flex-column flex-grow-1">

        <a href="{{ route('volunteer.dashboard') }}"
           class="nav-link {{ request()->routeIs('volunteer.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 ms-2"></i>
            لوحة التحكم
        </a>

        <a href="{{ route('volunteer.applications.index') }}"
           class="nav-link {{ request()->routeIs('volunteer.applications*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text ms-2"></i>
            طلباتي
        </a>

        <a href="{{ route('volunteer.profile') }}"
           class="nav-link {{ request()->routeIs('volunteer.profile*') ? 'active' : '' }}">
            <i class="bi bi-person ms-2"></i>
            ملفي الشخصي
        </a>

        <a href="{{ route('jobs.index') }}"
           class="nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }}">
            <i class="bi bi-briefcase ms-2"></i>
            فرص التطوع
        </a>

        <a href="{{ route('volunteer.notifications.index') }}"
           class="nav-link {{ request()->routeIs('volunteer.notifications*') ? 'active' : '' }}">
            <i class="bi bi-bell ms-2"></i>
            الإشعارات
            @if($unreadNotifications > 0)
                <span class="badge bg-danger rounded-pill ms-1">
                    {{ $unreadNotifications }}
                </span>
            @endif
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