<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Semua Pendaftaran Peserta</h1>
                <p class="text-sm text-slate-500 mt-1">Daftar seluruh pendaftaran workshop (Demonstrasi Query Optimization)</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-200">
                    Mode: {{ strtoupper($mode) }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <!-- Table (no pagination as specified to keep query counts clear) -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Order ID</th>
                            <th class="px-6 py-4">Nama Peserta</th>
                            <th class="px-6 py-4">Workshop</th>
                            <th class="px-6 py-4">Biaya</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Kode Tiket</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($registrations as $index => $r)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 text-xs font-bold text-slate-400">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs font-bold text-slate-800">
                                    {{ $r->order_id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $r->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $r->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">{{ $r->workshop->title }}</div>
                                    <div class="text-xs text-slate-400">{{ $r->workshop->speaker }}</div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $r->workshop->price == 0 ? 'Gratis' : 'Rp ' . number_format($r->workshop->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <x-status-badge :status="$r->status" />
                                </td>
                                <td class="px-6 py-4 font-mono text-xs font-semibold {{ $r->ticket_code ? 'text-primary-900 bg-primary-50 px-2 py-0.5 rounded' : 'text-slate-400' }}">
                                    {{ $r->ticket_code ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                    Belum ada data registrasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Query Metric Badge -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                <span class="text-xs text-slate-500">
                    Menampilkan seluruh {{ $registrations->count() }} pendaftaran (tanpa paginasi untuk akurasi demonstrasi query).
                </span>
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-slate-800 text-white shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>{{ $queryCount }} query &bull; {{ round($totalTimeMs, 2) }} ms</span>
                </span>
            </div>
        </div>
    </div>
</x-app-layout>