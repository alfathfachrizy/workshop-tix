<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pendaftaran & Tiket Saya</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola status keikutsertaan workshop dan akses tiket digital Anda</p>
            </div>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-primary-900 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-xs">
                + Ikuti Workshop Lain
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($registrations->isEmpty())
            <x-empty-state
                title="Belum Ada Pendaftaran Workshop"
                description="Anda belum mendaftar di workshop manapun. Jelajahi katalog dan daftarkan diri Anda sekarang!"
                action="Jelajahi Workshop"
                :actionUrl="route('home')"
            />
        @else
            <div class="space-y-4">
                @foreach($registrations as $reg)
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs hover:shadow-md transition flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="space-y-2 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <x-status-badge :status="$reg->status" />
                                <span class="font-mono text-xs text-slate-400 font-bold">
                                    {{ $reg->order_id }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-slate-900">
                                <a href="{{ route('workshops.show', $reg->workshop->slug) }}" class="hover:text-primary-900 transition">
                                    {{ $reg->workshop->title }}
                                </a>
                            </h3>

                            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>{{ $reg->workshop->speaker }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>{{ \Carbon\Carbon::parse($reg->workshop->starts_at)->translatedFormat('d M Y, H:i') }} WIB</span>
                                </div>
                                <div class="flex items-center gap-1.5 font-bold text-primary-900">
                                    <span>{{ $reg->workshop->price == 0 ? 'Gratis' : 'Rp ' . number_format($reg->workshop->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap items-center gap-3 shrink-0">
                            @if($reg->isPaid())
                                <a href="{{ route('tickets.show', $reg->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-xs transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                    Lihat E-Tiket
                                </a>
                            @elseif($reg->isPending())
                                <a href="{{ route('registrations.show', $reg->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-accent-500 hover:bg-accent-600 shadow-xs transition">
                                    Bayar Sekarang
                                </a>
                                <form action="{{ route('registrations.check-status', $reg->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Cek Status
                                    </button>
                                </form>
                            @else
                                <span class="text-xs font-semibold text-slate-400 italic">Pendaftaran Berakhir</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>