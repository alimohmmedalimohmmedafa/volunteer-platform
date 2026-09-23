@php
    $unreadMessagesCount = \App\Models\Message::where('is_read', false)->count();
@endphp

<div class="d-flex flex-column h-100">

    <div class="mb-4 sidebar-brand">
        <h5 class="fw-bold brand">
            <i class="bi bi-shield-lock-fill text-warning"></i>
            منصة التطوع
        </h5>
        <small>
            لوحة الإدارة
        </small>
    </div>

    <nav class="nav flex-column flex-grow-1">

        <div class="sidebar-section-title">نظرة عامة</div>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 ms-2"></i>
            لوحة التحكم
        </a>

        <div class="sidebar-section-title">الإدارة</div>

        <a href="{{ route('admin.organizations.index') }}"
           class="nav-link {{ request()->routeIs('admin.organizations*') ? 'active' : '' }}">
            <i class="bi bi-building ms-2"></i>
            المنظمات
        </a>

        <a href="{{ route('admin.jobs.index') }}"
           class="nav-link {{ request()->routeIs('admin.jobs*') ? 'active' : '' }}">
            <i class="bi bi-briefcase ms-2"></i>
            الوظائف
        </a>

        <a href="{{ route('admin.applications.index') }}"
           class="nav-link {{ request()->routeIs('admin.applications*') ? 'active' : '' }}">
            <i class="bi bi-people ms-2"></i>
            الطلبات
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill ms-2"></i>
            المستخدمون
        </a>

        <a href="{{ route('admin.employees.index') }}"
           class="nav-link {{ request()->routeIs('admin.employees*') ? 'active' : '' }}">
            <i class="bi bi-person-badge ms-2"></i>
            الموظفون
        </a>

        <div class="sidebar-section-title">المراسلات</div>

        <a href="{{ route('admin.messages.index') }}"
           class="nav-link {{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
            <i class="bi bi-envelope ms-2"></i>
            الرسائل
            @if($unreadMessagesCount)
                <span class="badge badge-counter ms-auto">
                    {{ $unreadMessagesCount }}
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
