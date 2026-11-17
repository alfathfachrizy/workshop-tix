<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard Panitia</h1>
                <p class="text-sm text-slate-500 mt-1">Ringkasan pendaftaran, performa workshop, dan monitoring transaksi</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('panitia.workshops.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-900 hover:bg-primary-800 text-white font-semibold text-sm rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Workshop
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Stat Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <x-stat-card
                title="Total Workshop"
                :value="$totalWorkshops"
                color="primary"
                subtext="Aktif di katalog"
            >
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </x-slot>
            </x-stat-card>

            <x-stat-card
                title="Total Pendaftar"
                :value="$totalRegistrations"
                color="purple"
                subtext="Seluruh status"
            >
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </x-slot>
            </x-stat-card>

            <x-stat-card
                title="Pendaftar Lunas"
                :value="$totalPaid"
                color="emerald"
                subtext="Tiket terbit"
            >
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </x-slot>
            </x-stat-card>

            <x-stat-card
                title="Menunggu Bayar"
                :value="$totalPending"
                color="accent"
                subtext="Pending payment"
            >
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </x-slot>
            </x-stat-card>

            <x-stat-card
                title="Total Revenue"
                :value="'Rp ' . number_format($totalRevenue, 0, ',', '.')"
                color="primary"
                subtext="Dari peserta lunas"
            >
                <x-slot name="icon">
                    <svg class="w-6 h-6 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </x-slot>
            </x-stat-card>
        </div>

        <!-- Recent Registrations Section -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Pendaftaran Terbaru</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar registrasi peserta terkini masuk ke sistem</p>
                </div>
                <a href="{{ route('panitia.registrations.index') }}" class="text-xs font-bold text-primary-900 hover:text-primary-700 transition">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Order ID</th>
                            <th class="px-6 py-4">Peserta</th>
                            <th class="px-6 py-4">Workshop</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentRegistrations as $reg)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-mono text-xs font-bold text-slate-800">
                                    {{ $reg->order_id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">{{ $reg->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $reg->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate font-medium text-slate-700">
                                    {{ $reg->workshop->title }}
                                </td>
                                <td class="px-6 py-4">
                                    <x-status-badge :status="$reg->status" />
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400">
                                    {{ $reg->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                    Belum ada pendaftaran yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>