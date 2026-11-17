<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan | WorkshopTIX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans text-slate-800 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full text-center bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl">
        <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4 border border-amber-200">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <span class="inline-block px-3 py-1 text-xs font-bold text-amber-700 bg-amber-100 rounded-full mb-3">ERROR 404</span>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Halaman Tidak Ditemukan</h1>
        <p class="mt-2 text-sm text-slate-600">
            Halaman atau workshop yang Anda cari tidak tersedia, telah dihapus, atau tautan yang Anda tuju keliru.
        </p>
        <div class="mt-6 flex flex-col sm:flex-row gap-2 justify-center">
            <a href="{{ url('/') }}" class="inline-flex justify-center items-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary-900 hover:bg-primary-800 shadow-sm transition">
                Kembali ke Beranda
            </a>
        </div>
        <p class="mt-8 text-xs text-slate-400">SMK PESAT • RPL • WorkshopTIX</p>
    </div>
</body>
</html>