<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="apple-shell font-sans antialiased">
        <div class="min-h-screen">
            @include('layouts.navigation')

            @isset($header)
                <header class="border-b border-black/5 bg-white/80 backdrop-blur-xl">
                    <div class="apple-wrap py-6">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="apple-wrap py-8 sm:py-10">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
