<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'لوحة الموظف')
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')

</head>

<body>

<div class="container-fluid">

    <div class="row">

        {{-- Sidebar --}}
        <aside class="col-lg-2 p-3 app-sidebar employee-sidebar">

            <div class="mb-4">
                <h5 class="fw-bold brand">
                    <i class="bi bi-person-badge-fill text-success"></i>
                    منصة التطوع
                </h5>

                <small>
                    لوحة الموظف
                </small>
            </div>

            @php
                $unreadMessagesCount = \App\Models\Message::where('is_read', false)->count();
            @endphp

            <nav class="nav flex-column">

                <a href="{{ route('employee.dashboard') }}"
                   class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 ms-2"></i>
                    لوحة التحكم
                </a>

                <a href="{{ route('employee.organizations.index') }}"
                   class="nav-link {{ request()->routeIs('employee.organizations*') ? 'active' : '' }}">
                    <i class="bi bi-building ms-2"></i>
                    المنظمات
                </a>

                <a href="{{ route('employee.jobs.index') }}"
                   class="nav-link {{ request()->routeIs('employee.jobs*') ? 'active' : '' }}">
                    <i class="bi bi-briefcase ms-2"></i>
                    الوظائف
                </a>

                <a href="{{ route('employee.applications.index') }}"
                   class="nav-link {{ request()->routeIs('employee.applications*') ? 'active' : '' }}">
                    <i class="bi bi-people ms-2"></i>
                    الطلبات
                </a>

                <a href="{{ route('employee.messages.index') }}"
                   class="nav-link {{ request()->routeIs('employee.messages*') ? 'active' : '' }}">
                    <i class="bi bi-envelope ms-2"></i>
                    الرسائل
                    @if($unreadMessagesCount)
                        <span class="badge badge-counter ms-auto">
                            {{ $unreadMessagesCount }}
                        </span>
                    @endif
                </a>

                <hr class="border-secondary">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="nav-link logout border-0 bg-transparent w-100 text-end">
                        <i class="bi bi-box-arrow-right ms-2"></i>
                        تسجيل الخروج
                    </button>
                </form>

            </nav>

        </aside>

        {{-- Content --}}
        <main class="col-lg-10 p-3 p-lg-4 app-content">

            <x-flash />

            @yield('content')

        </main>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>