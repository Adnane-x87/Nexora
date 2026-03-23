<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'NEXORA — Auth')</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet"/>
    @vite(['resources/css/login.css'])
    @stack('styles')
</head>
<body>
    <div class="bg-grid"></div>
    <div class="bg-glow"></div>

    <div class="login-wrap">
        <a href="{{ url('/') }}" class="login-logo">NEX<span>ORA</span></a>
        @yield('content')
        <div class="back-link"><a href="{{ url('/') }}">← Back to Store</a></div>
    </div>

    @stack('scripts')
</body>
</html>
