@php
    $routeName = request()->route()?->getName();
    $isHome = $routeName === 'home';
    $isJobs = in_array($routeName, ['jobs.index', 'jobs.show'], true);
    $isOrganizations = in_array($routeName, ['organizations.index', 'organizations.show'], true);

    $dashboardRoute = null;
    if (auth()->check()) {
        $dashboardRoute = match (auth()->user()->role) {
            'admin' => 'admin.dashboard',
            'employee' => 'employee.dashboard',
            'organization' => 'organization.dashboard',
            default => 'volunteer.dashboard',
        };
    }
@endphp

<nav class="navbar navbar-expand-lg app-navbar sticky-top" aria-label="القائمة الرئيسية">
    <div class="container">

        <a class="navbar-brand" href="{{ route('home') }}">
            <span class="brand-icon" aria-hidden="true">
                <i class="bi bi-heart-fill"></i>
            </span>
            منصة التطوع
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#publicNavbar"
            aria-controls="publicNavbar"
            aria-expanded="false"
            aria-label="فتح أو إغلاق القائمة"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="publicNavbar">

            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a
                        class="nav-link {{ $isHome ? 'active' : '' }}"
                        href="{{ route('home') }}"
                        @if($isHome) aria-current="page" @endif
                    >
                        الرئيسية
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link {{ $isJobs ? 'active' : '' }}"
                        href="{{ route('jobs.index') }}"
                        @if($isJobs) aria-current="page" @endif
                    >
                        فرص التطوع
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link {{ $isOrganizations ? 'active' : '' }}"
                        href="{{ route('organizations.index') }}"
                        @if($isOrganizations) aria-current="page" @endif
                    >
                        المنظمات
                    </a>
                </li>

                @if($isHome)
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">
                            كيف تعمل المنصة؟
                        </a>
                    </li>
                @endif

            </ul>

            <div class="d-flex flex-wrap gap-2 navbar-actions">

                @auth

                    <a href="{{ $dashboardRoute ? route($dashboardRoute) : route('dashboard') }}"
                       class="btn btn-primary btn-icon">
                        <i class="bi bi-speedometer2" aria-hidden="true"></i>
                        لوحة التحكم
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf

                        <button type="submit" class="btn btn-outline-secondary btn-icon">
                            <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                            تسجيل الخروج
                        </button>
                    </form>

                @else

                    <a href="{{ route('login') }}" class="btn btn-outline-primary">
                        تسجيل الدخول
                    </a>

                    <a href="{{ route('register') }}" class="btn btn-primary">
                        إنشاء حساب
                    </a>

                @endauth

            </div>

        </div>

    </div>
</nav>