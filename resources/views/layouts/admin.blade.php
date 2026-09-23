<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'لوحة المدير')
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

<a class="skip-link" href="#main-content">تخطى إلى المحتوى الرئيسي</a>

<div class="d-lg-none mobile-topbar bg-white border-bottom shadow-sm px-3 py-2 position-fixed top-0 start-0 end-0 z-3">
    <div class="d-flex align-items-center justify-content-between">
        <button
            class="btn btn-link text-dark p-1"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#adminSidebarOffcanvas"
            aria-controls="adminSidebarOffcanvas"
            aria-label="فتح القائمة">
            <i class="bi bi-list fs-4"></i>
        </button>

        <a class="fw-bold text-decoration-none" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-shield-lock-fill text-primary ms-1"></i>
            منصة التطوع
        </a>

        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link text-danger p-1" aria-label="تسجيل الخروج">
                <i class="bi bi-box-arrow-right fs-5"></i>
            </button>
        </form>
    </div>
</div>

<div class="container-fluid">
    <div class="row">

        {{-- Offcanvas sidebar (mobile) --}}
        <div class="offcanvas offcanvas-end admin-offcanvas"
             tabindex="-1"
             id="adminSidebarOffcanvas"
             aria-label="قائمة المدير">

            <div class="offcanvas-body p-3 admin-sidebar-oc">
                @include('partials._admin-sidebar-nav')
            </div>
        </div>

        {{-- Desktop sidebar --}}
        <aside class="col-lg-2 d-none d-lg-block p-3 app-sidebar admin-sidebar">
            @include('partials._admin-sidebar-nav')
        </aside>

        {{-- Content --}}
        <main id="main-content" class="col-lg-10 p-3 p-lg-4 app-content mt-mobile-topbar">

            <x-flash />

            @yield('content')

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>
