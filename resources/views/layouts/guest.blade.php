<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WorkshopTIX') }} - Masuk / Daftar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-800 antialiased bg-slate-50 min-h-screen flex flex-col justify-between p-4 sm:p-6">
    <div class="w-full max-w-md mx-auto my-auto py-8">
        <div class="flex justify-center mb-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-900 to-primary-700 flex items-center justify-center text-white shadow-lg shadow-primary-900/25 group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-6 h-6 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
                <div class="flex flex-col text-left">
                    <span class="text-2xl font-black tracking-tight text-primary-900">Workshop<span class="text-accent-500">TIX</span></span>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 -mt-1">SMK PESAT RPL</span>
                </div>
            </a>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xl">
            {{ $slot }}
        </div>
    </div>

    <div class="text-center text-xs text-slate-400 pb-2">
        SMK PESAT • Rekayasa Perangkat Lunak &copy; {{ date('Y') }}
    </div>
</body>
</html>