<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
          content="منصة تطوع تربط المتطوعين بفرص تطوعية ذات أثر، وتوصل المنظمات بمتطوعين مناسبين لخدمة المجتمع.">

    <title>@yield('title', 'منصة التطوع')</title>

    {{-- Bootstrap 5 RTL --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    {{-- Design System --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>

<body>

    <a class="skip-link" href="#main-content">تخطى إلى المحتوى الرئيسي</a>

    <x-navbar />

    <main id="main-content" class="app-main">

        @if(session('success') || session('error') || (isset($errors) && $errors->any()))
            <div class="container mt-3">
                <x-flash />
            </div>
        @endif

        @yield('content')

    </main>

    <x-footer />

    {{-- Bootstrap JavaScript --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    {{-- Alpine.js (interactive UI components) --}}
    <script
        defer
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js">
    </script>

    @stack('scripts')

</body>

</html>