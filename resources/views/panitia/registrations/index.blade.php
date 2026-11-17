<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Semua Pendaftaran Peserta</h1>
                <p class="text-sm text-slate-500 mt-1">Studi Kasus & Komparasi Optimasi Query Eloquent (N+1 Problem)</p>
            </div>
            <!-- Mode Switcher Tabs -->
            <div class="inline-flex items-center p-1 bg-slate-200/80 rounded-2xl">
                <a
                    href="{{ route('panitia.registrations.index', ['mode' => 'eager']) }}"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $mode === 'eager' ? 'bg-white text-primary-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    ⚡ Eager Loading (Default)
                </a>
                <a
                    href="{{ route('panitia.registrations.index', ['mode' => 'lazy']) }}"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $mode === 'lazy' ? 'bg-white text-rose-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    🐢 Lazy Loading (N+1)
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <!-- N+1 Benchmark Comparison Panel -->
        <div class="bg-gradient-to-br from-slate-900 via-primary-950 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-800">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-white/10">
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-orange-300 border border-white/20 mb-2">
                        Benchmark Performa Eloquent ORM
                    </span>
                    <h2 class="text-xl sm:text-2xl font-black tracking-tight">
                        Komparasi Efisiensi Query Database
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl">
                        Menganalisis dampak N+1 Query Problem saat merender relasi <code class="bg-black/30 px-1.5 py-0.5 rounded text-orange-300">user</code> dan <code class="bg-black/30 px-1.5 py-0.5 rounded text-orange-300">workshop</code> pada {{ $totalRecords }} data pendaftaran.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="text-center p-3 rounded-2xl bg-white/5 border border-white/10">
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Total Baris Data</span>
                        <span class="text-xl font-black text-white">{{ $totalRecords }}</span>
                    </div>
                    <div class="text-center p-3 rounded-2xl {{ $mode === 'eager' ? 'bg-emerald-500/20 border-emerald-500/40' : 'bg-rose-500/20 border-rose-500/40' }} border">
                        <span class="text-[10px] uppercase font-semibold block {{ $mode === 'eager' ? 'text-emerald-300' : 'text-rose-300' }}">Mode Aktif</span>
                        <span class="text-xl font-black {{ $mode === 'eager' ? 'text-emerald-400' : 'text-rose-400' }}">{{ strtoupper($mode) }}</span>
                    </div>
                </div>
            </div>

            <!-- Comparison Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                <!-- Lazy Card -->
                <div class="p-5 rounded-2xl border {{ $mode === 'lazy' ? 'bg-rose-950/40 border-rose-500/50 ring-2 ring-rose-500/20' : 'bg-white/5 border-white/10 opacity-75' }}">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-rose-400 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            Mode Lazy Loading (Problematic)
                        </span>
                        @if($mode === 'lazy')
                            <span class="text-[10px] uppercase font-black bg-rose-500/30 text-rose-300 px-2 py-0.5 rounded">Sedang Berjalan</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-300 mb-4">
                        Mengambil pendaftaran dengan <code class="text-rose-300">Registration::all()</code>. Relasi user dan workshop dipanggil ulang di setiap baris loop tabel.
                    </p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-rose-400">~{{ $theoreticalLazyQueries }}</span>
                        <span class="text-xs text-slate-400">Queries (1 + {{ $totalRecords }} user + {{ $totalRecords }} workshop)</span>
                    </div>
                </div>

                <!-- Eager Card -->
                <div class="p-5 rounded-2xl border {{ $mode === 'eager' ? 'bg-emerald-950/40 border-emerald-500/50 ring-2 ring-emerald-500/20' : 'bg-white/5 border-white/10 opacity-75' }}">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-emerald-400 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Mode Eager Loading (Optimized)
                        </span>
                        @if($mode === 'eager')
                            <span class="text-[10px] uppercase font-black bg-emerald-500/30 text-emerald-300 px-2 py-0.5 rounded">Sedang Berjalan</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-300 mb-4">
                        Mengambil data sekaligus menggunakan <code class="text-emerald-300">Registration::with(['user', 'workshop'])->get()</code> via klausa <code class="text-emerald-300">WHERE IN</code>.
                    </p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-emerald-400">{{ $theoreticalEagerQueries }}</span>
                        <span class="text-xs text-slate-400">Queries (Hanya 3 query terlepas dari berapapun jumlah baris data)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table (no pagination as specified to keep query counts clear) -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Tabel Registrasi Seluruh Peserta</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Iterasi data tabel berikut mengeksekusi query relasi sesuai mode yang dipilih</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-slate-500">Status Filter: Semua ({{ $totalRecords }} Pendaftar)</span>
                </div>
            </div>

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
                                <td class="px-6 py-4 font-mono text-xs font-semibold {{ $r->ticket_code ? 'text-primary-900 bg-primary-50 px-2.5 py-1 rounded-md inline-block my-2' : 'text-slate-400' }}">
                                    {{ $r->ticket_code ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                    Belum ada data pendaftaran yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Query Metric Badge (evaluated after the loop finishes) -->
            <div class="p-4 sm:p-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-slate-500">
                    Menampilkan total <strong class="text-slate-800">{{ $totalRecords }}</strong> baris pendaftaran tanpa paginasi.
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-black shadow-xs {{ $mode === 'eager' ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">
                        <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                        <span>{{ $queryStats->count }} query &bull; {{ round($queryStats->time, 2) }} ms</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>