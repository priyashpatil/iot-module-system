<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Page') - IOT Monitoring System</title>

    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href={{ route('dashboard') }}>
                <x-logo-mark />
                IOT Monitoring System
            </a>
        </div>
    </nav>

    <div class="container py-2">
        <x-alert />
        @yield('content')
    </div>

    <div class="container small text-muted my-3">
        <footer>&copy; {{ date('Y') }} IOT Modules System</footer>
    </div>

    @stack('scripts')
</body>

</html>
