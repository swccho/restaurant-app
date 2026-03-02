<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Print') — {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: system-ui, sans-serif; font-size: 14px; line-height: 1.4; color: #111; margin: 0; padding: 16px; }
        @media print { body { padding: 0; } }
        .no-print { display: none; }
        @media screen { .no-print { display: block; } }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')
    @stack('scripts')
</body>
</html>
