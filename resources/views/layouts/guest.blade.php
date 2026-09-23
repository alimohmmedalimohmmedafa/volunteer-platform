<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'منصة التطوع' }}</title>

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

<body class="auth-page">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5 col-xl-4">

                <main id="auth-content">

                    <div class="auth-card">

                        <a href="{{ route('home') }}" class="auth-brand">
                            <span class="auth-brand-icon" aria-hidden="true">
                                <i class="bi bi-heart-fill"></i>
                            </span>
                            <span class="auth-brand-name">منصة التطوع</span>
                        </a>

                        {{ $slot }}

                    </div>

                    <p class="text-center text-muted small mt-4 mb-0">
                        © {{ date('Y') }} منصة التطوع. جميع الحقوق محفوظة.
                    </p>

                </main>

            </div>
        </div>
    </div>

    {{-- Bootstrap JavaScript --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    {{-- Alpine.js (interactive form fields UI) --}}
    <script
        defer
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js">
    </script>

    @stack('scripts')

</body>

</html>