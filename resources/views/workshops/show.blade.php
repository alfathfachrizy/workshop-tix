<x-app-layout>
    @php
        $remaining = $workshop->remaining_quota;
        $isFull = $remaining <= 0;
        $formattedPrice = $workshop->price == 0 ? 'Gratis' : 'Rp ' . number_format($workshop->price, 0, ',', '.');
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-primary-900 transition-colors">Beranda</a>
            <span>/</span>
            <span class="text-slate-800 font-medium truncate">{{ $workshop->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Main Details -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-800">
                            Workshop IT Terpadu
                        </span>
                        @if($isFull)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                Kuota Penuh
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Sisa Kuota: {{ $remaining }} Kursi
                            </span>
                        @endif
                    </div>

                    <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                        {{ $workshop->title }}
                    </h1>

                    <div class="mt-6 border-y border-slate-100 py-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-slate-600">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-900 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block font-medium">Pemateri / Trainer</span>
                                <span class="font-bold text-slate-800">{{ $workshop->speaker }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-900 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block font-medium">Jadwal Pelaksanaan</span>
                                <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($workshop->starts_at)->translatedFormat('l, d F Y - H:i') }} WIB</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-900 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block font-medium">Lokasi Pelaksanaan</span>
                                <span class="font-bold text-slate-800">{{ $workshop->location }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-900 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block font-medium">Kapasitas Maksimal</span>
                                <span class="font-bold text-slate-800">{{ $workshop->quota }} Peserta</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-3">Deskripsi & Materi Pembelajaran</h3>
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed whitespace-pre-line">
                            {{ $workshop->description }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar / Action Card -->
            <div class="lg:col-span-4">
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-md sticky top-24">
                    <span class="text-xs uppercase tracking-wider text-slate-500 font-bold block mb-1">Biaya Investasi</span>
                    <div class="text-3xl font-black text-primary-900 mb-4">
                        {{ $formattedPrice }}
                    </div>

                    <div class="space-y-3 mb-6 border-y border-slate-100 py-4 text-xs text-slate-600">
                        <div class="flex items-center justify-between">
                            <span>Status Pendaftaran</span>
                            <span class="font-semibold {{ $isFull ? 'text-rose-600' : 'text-emerald-600' }}">
                                {{ $isFull ? 'Ditutup (Penuh)' : 'Dibuka' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Sisa Kursi</span>
                            <span class="font-semibold text-slate-900">{{ $remaining }} dari {{ $workshop->quota }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>E-Sertifikat & Tiket</span>
                            <span class="font-semibold text-emerald-600">Tersedia (QR)</span>
                        </div>
                    </div>

                    @guest
                        <div class="space-y-3">
                            <p class="text-xs text-slate-500 text-center">Silakan masuk untuk mendaftar workshop ini</p>
                            <a href="{{ route('login') }}" class="block w-full py-3 bg-primary-900 hover:bg-primary-800 text-white font-bold text-sm text-center rounded-xl shadow-md transition">
                                Masuk untuk Mendaftar
                            </a>
                            <a href="{{ route('register') }}" class="block w-full py-2.5 border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs text-center rounded-xl transition">
                                Buat Akun Baru
                            </a>
                        </div>
                    @else
                        @if($userRegistration)
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 mb-4 text-center">
                                <span class="text-xs text-slate-500 block mb-1">Status Pendaftaran Anda:</span>
                                <x-status-badge :status="$userRegistration->status" class="mb-3" />

                                @if($userRegistration->isPaid())
                                    <a href="{{ route('tickets.show', $userRegistration->id) }}" class="block w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm text-center rounded-xl shadow-sm transition">
                                        Lihat Tiket Digital
                                    </a>
                                @elseif($userRegistration->isPending())
                                    <a href="{{ route('registrations.show', $userRegistration->id) }}" class="block w-full py-2.5 bg-accent-500 hover:bg-accent-600 text-white font-bold text-sm text-center rounded-xl shadow-sm transition">
                                        Lanjutkan Pembayaran
                                    </a>
                                @else
                                    <p class="text-xs text-rose-600">Pendaftaran sebelumnya {{ $userRegistration->status }}.</p>
                                @endif
                            </div>
                        @else
                            @if($isFull)
                                <button type="button" disabled class="w-full py-3 bg-slate-200 text-slate-500 font-bold text-sm rounded-xl cursor-not-allowed">
                                    Kuota Sudah Penuh
                                </button>
                            @else
                                <form action="{{ route('workshops.register', $workshop->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-3.5 bg-accent-500 hover:bg-accent-600 text-white font-extrabold text-sm rounded-xl shadow-md hover:shadow-lg transition duration-200">
                                        Daftar Workshop Sekarang
                                    </button>
                                </form>
                                <p class="text-[11px] text-slate-400 text-center mt-2">
                                    Satu akun hanya dapat mendaftar 1 kali per workshop
                                </p>
                            @endif
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </div>
</x-app-layout>