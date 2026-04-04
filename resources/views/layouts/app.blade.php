<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'جمع الدورات التعليمية من يوتيوب')</title>

    {{-- Bootstrap 5 RTL --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap"
          rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('head')
</head>
<body>

    {{-- ── Top Navigation ──────────────────────────────────────────────── --}}
    <nav class="navbar navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('courses.index') }}">
                <i class="bi bi-play-circle-fill text-danger me-1"></i>
                YouTube Courses
            </a>
        </div>
    </nav>

    {{-- ── Flash Messages ───────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="alert-banner alert-banner--success">
            <div class="container d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-banner alert-banner--danger">
            <div class="container d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- ── Page Content ─────────────────────────────────────────────────── --}}
    @yield('content')

    <footer class="footer mt-5 py-4 text-center text-muted small">
        <div class="container">
            YouTube Course Scraper &mdash; Powered by Anthropic Claude + YouTube Data API v3
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
