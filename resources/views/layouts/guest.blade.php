<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Estate') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center bg-slate-50 px-4 py-10">
            <div class="flex flex-col items-center">
                <a href="/" class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-lg font-bold text-white shadow-sm">
                    E
                </a>
                <h1 class="mt-4 text-xl font-bold text-gray-900">{{ $heading ?? '' }}</h1>
                @isset($subheading)
                    <p class="mt-1 text-sm text-gray-500">{{ $subheading }}</p>
                @endisset
            </div>

            <div class="mt-6 w-full max-w-md rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="mt-5 text-sm text-gray-500">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </body>
</html>
