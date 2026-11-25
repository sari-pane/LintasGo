<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'LintasGo') . ' - Sistem Pemesanan Tiket Travel')</title>


        {{-- Vite Assets --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="@yield('body-class', 'font-sans antialiased text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-slate-950 min-h-screen flex flex-col')">
        @yield('content')

        @stack('scripts')
    </body>
</html>
