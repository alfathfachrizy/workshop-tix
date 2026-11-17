<x-app-layout>
    @php
        $snapUrl = config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
        $clientKey = config('services.midtrans.client_key');
        $formattedPrice = $registration->workshop->price == 0 ? 'Gratis' : 'Rp ' . number_format($registration->workshop->price, 0, ',', '.');
    @endphp

    @push('scripts')
        @if($clientKey && $registration->isPending())
            <script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
        @endif
    @endpush

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-lg">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-primary-950 to-primary-900 text-white p-6 sm:p-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <span class="text-xs uppercase font-bold text-orange-400 tracking-wider block mb-1">Menunggu Pembayaran</span>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Rincian Pembayaran</h1>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block">Nomor Order:</span>
                        <span class="font-mono text-sm font-bold text-white bg-white/10 px-3 py-1 rounded-lg border border-white/20">
                            {{ $registration->order_id }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Body Details -->
            <div class="p-6 sm:p-8 space-y-6">
                <!-- Status Row -->
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-200">
                    <div>
                        <span class="text-xs text-slate-500 block font-medium">Status Saat Ini</span>
                        <span class="text-sm font-bold text-slate-800">
                            {{ $registration->isPending() ? 'Menunggu Penyelesaian Pembayaran' : ucfirst($registration->status) }}
                        </span>
                    </div>
                    <x-status-badge :status="$registration->status" />
                </div>

                <!-- Workshop Item Detail -->
                <div class="border-t border-b border-slate-100 py-6 space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Workshop Yang Didaftarkan</h3>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">{{ $registration->workshop->title }}</h2>
                            <p class="text-xs text-slate-500 mt-1">Pemateri: {{ $registration->workshop->speaker }} &bull; {{ $registration->workshop->location }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">Jadwal: {{ \Carbon\Carbon::parse($registration->workshop->starts_at)->translatedFormat('l, d F Y - H:i') }} WIB</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <span class="text-xs text-slate-400 block">Total Tagihan</span>
                            <span class="text-2xl font-black text-primary-900">{{ $formattedPrice }}</span>
                        </div>
                    </div>
                </div>

                <!-- Participant Info -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-slate-600 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <div>
                        <span class="text-slate-400 block font-medium">Nama Peserta</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $registration->user->name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Email Terdaftar</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $registration->user->email }}</span>
                    </div>
                </div>

                <!-- Payment Action Area -->
                <div class="pt-4 space-y-4">
                    @if($registration->isPending())
                        @if($registration->snap_token)
                            <button
                                type="button"
                                id="pay-button"
                                class="w-full py-4 bg-accent-500 hover:bg-accent-600 text-white font-extrabold text-base rounded-2xl shadow-lg hover:shadow-xl transition transform active:scale-[0.99] flex items-center justify-center gap-2"
                            >
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Bayar Sekarang dengan Midtrans Snap
                            </button>
                        @else
                            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs">
                                Token pembayaran sedang disiapkan atau konfigurasi sandbox belum aktif. Gunakan tombol Cek Status di bawah setelah membayar.
                            </div>
                        @endif

                        <div class="flex flex-col sm:flex-row gap-3">
                            <form action="{{ route('registrations.check-status', $registration->id) }}" method="POST" class="w-full sm:w-1/2">
                                @csrf
                                <button
                                    type="submit"
                                    id="check-status-button"
                                    class="w-full py-3 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-sm rounded-xl transition flex items-center justify-center gap-2 shadow-xs"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Cek Status Pembayaran
                                </button>
                            </form>

                            <a href="{{ route('registrations.my') }}" class="w-full sm:w-1/2 py-3 text-center border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-sm rounded-xl transition">
                                Kembali ke Tiket Saya
                            </a>
                        </div>
                    @elseif($registration->isPaid())
                        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-center space-y-3">
                            <p class="text-sm font-bold text-emerald-800">Pembayaran Berhasil Diverifikasi!</p>
                            <a href="{{ route('tickets.show', $registration->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-md transition">
                                Buka E-Tiket Digital
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($registration->isPending() && $registration->snap_token)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var payButton = document.getElementById('pay-button');
                if (payButton && window.snap) {
                    payButton.addEventListener('click', function () {
                        window.snap.pay('{{ $registration->snap_token }}', {
                            onSuccess: function (result) {
                                // Important: Frontend NEVER marks paid directly!
                                // It only reloads or submits status check
                                window.location.href = "{{ route('registrations.show', $registration->id) }}?check=1";
                            },
                            onPending: function (result) {
                                window.location.href = "{{ route('registrations.show', $registration->id) }}";
                            },
                            onError: function (result) {
                                alert('Pembayaran gagal atau dibatalkan.');
                                window.location.href = "{{ route('registrations.show', $registration->id) }}";
                            },
                            onClose: function () {
                                // User closed popup without finishing
                            }
                        });
                    });
                }
            });
        </script>
    @endif
</x-app-layout>