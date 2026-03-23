<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'NEXORA — Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet"/>
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css'])
    @stack('styles')
</head>
<body>
    <x-sidebar :activePage="$activePage ?? 'statistics'" />

    <main class="main">
        @yield('content')
    </main>

    <script>
        lucide.createIcons();
    </script>
    @vite(['resources/js/dashboard.js'])
    @stack('scripts')
</body>
</html>
