<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Action Toolbar (hidden on print) -->
        <div class="flex items-center justify-between mb-6 print:hidden">
            <a href="{{ route('registrations.my') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-primary-900 transition">
                &larr; Kembali ke Tiket Saya
            </a>

            <button
                type="button"
                onclick="window.print()"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-900 hover:bg-primary-800 text-white font-bold text-sm rounded-xl shadow-xs hover:shadow-md transition cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </button>
        </div>

        <!-- Ticket Card -->
        <div id="printable-ticket" class="bg-white rounded-3xl border-2 border-primary-900/10 shadow-2xl overflow-hidden relative">
            <!-- Top Header Ribbon -->
            <div class="bg-gradient-to-r from-primary-950 via-primary-900 to-primary-800 text-white p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-accent-500 font-black text-xl shadow-inner">
                        W
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-extrabold tracking-tight">Workshop<span class="text-accent-500">TIX</span></span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-accent-500 text-white">E-Ticket</span>
                        </div>
                        <p class="text-xs text-slate-300">SMK PESAT IT XPRO • Rekayasa Perangkat Lunak</p>
                    </div>
                </div>

                <div class="sm:text-right">
                    <span class="text-[11px] uppercase tracking-wider text-slate-300 block font-semibold">Kode Tiket Digital</span>
                    <span class="font-mono text-xl sm:text-2xl font-black text-orange-400 tracking-wider">
                        {{ $registration->ticket_code }}
                    </span>
                </div>
            </div>

            <!-- Perforated Line Decoration -->
            <div class="relative flex items-center justify-between bg-white px-2 -my-3 z-10">
                <div class="w-6 h-6 rounded-full bg-slate-50 border-r border-slate-200 -ml-5"></div>
                <div class="w-full border-t-2 border-dashed border-slate-200 mx-2"></div>
                <div class="w-6 h-6 rounded-full bg-slate-50 border-l border-slate-200 -mr-5"></div>
            </div>

            <!-- Ticket Content -->
            <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                <!-- Left Details (8 cols) -->
                <div class="md:col-span-8 space-y-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-primary-900 block mb-1">Workshop Terdaftar</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                            {{ $registration->workshop->title }}
                        </h2>
                        <p class="text-sm font-semibold text-slate-600 mt-1">Pemateri: {{ $registration->workshop->speaker }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-100 text-xs">
                        <div>
                            <span class="text-slate-400 block font-medium">Nama Peserta</span>
                            <span class="text-sm font-bold text-slate-800">{{ $registration->user->name }}</span>
                            <span class="text-slate-500 block truncate">{{ $registration->user->email }}</span>
                        </div>

                        <div>
                            <span class="text-slate-400 block font-medium">Status Pembayaran</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                LUNAS / VERIFIED
                            </span>
                        </div>

                        <div>
                            <span class="text-slate-400 block font-medium">Jadwal & Waktu</span>
                            <span class="text-sm font-bold text-slate-800">
                                {{ \Carbon\Carbon::parse($registration->workshop->starts_at)->translatedFormat('l, d F Y') }}
                            </span>
                            <span class="text-slate-600 block">Pukul {{ \Carbon\Carbon::parse($registration->workshop->starts_at)->format('H:i') }} WIB</span>
                        </div>

                        <div>
                            <span class="text-slate-400 block font-medium">Lokasi Pelaksanaan</span>
                            <span class="text-sm font-bold text-slate-800">{{ $registration->workshop->location }}</span>
                            <span class="text-slate-500 block">SMK PESAT Bogor</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-400">
                        <span>Nomor Order: <strong class="font-mono text-slate-600">{{ $registration->order_id }}</strong></span>
                        <span>Dibayar pada: {{ $registration->paid_at ? \Carbon\Carbon::parse($registration->paid_at)->translatedFormat('d M Y H:i') : '-' }}</span>
                    </div>
                </div>

                <!-- Right QR Code (4 cols) -->
                <div class="md:col-span-4 flex flex-col items-center justify-center p-6 bg-slate-50 rounded-2xl border border-slate-200 text-center">
                    <div class="bg-white p-3 rounded-xl shadow-xs border border-slate-200">
                        {!! $qrCode !!}
                    </div>
                    <span class="mt-3 font-mono text-xs font-bold text-slate-800 tracking-wider">
                        {{ $registration->ticket_code }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1 max-w-[160px]">
                        Tunjukkan QR Code ini kepada panitia saat registrasi ulang di lokasi
                    </p>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="bg-slate-50 px-6 sm:px-8 py-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>WorkshopTIX &bull; LKPD XI RPL &bull; SMK PESAT</span>
                <span>Harap hadir 15 menit sebelum acara dimulai</span>
            </div>
        </div>
    </div>

    <!-- Print Specific CSS -->
    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            nav, footer, .print\:hidden {
                display: none !important;
            }
            #printable-ticket {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                margin: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</x-app-layout>