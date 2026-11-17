<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kelola Workshop</h1>
                <p class="text-sm text-slate-500 mt-1">Daftar semua workshop IT yang diselenggarakan</p>
            </div>
            <a href="{{ route('panitia.workshops.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-900 hover:bg-primary-800 text-white font-semibold text-sm rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Workshop Baru
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <!-- Search filter -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <form action="{{ route('panitia.workshops.index') }}" method="GET" class="flex items-center gap-3 w-full max-w-md">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari judul workshop atau pemateri..."
                    class="w-full text-sm rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-primary-500"
                />
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm font-semibold rounded-xl hover:bg-slate-700 transition">
                    Cari
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs" x-data="{ deleteModalOpen: false, deleteActionUrl: '', deleteTitle: '' }">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Judul & Pemateri</th>
                            <th class="px-6 py-4">Jadwal & Lokasi</th>
                            <th class="px-6 py-4">Biaya</th>
                            <th class="px-6 py-4">Kuota / Pendaftar</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($workshops as $workshop)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 max-w-sm">
                                    <a href="{{ route('workshops.show', $workshop->slug) }}" target="_blank" class="font-bold text-slate-900 hover:text-primary-900 transition line-clamp-1">
                                        {{ $workshop->title }}
                                    </a>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $workshop->speaker }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <div class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($workshop->starts_at)->translatedFormat('d M Y, H:i') }} WIB</div>
                                    <div class="text-slate-400 mt-0.5 truncate max-w-xs">{{ $workshop->location }}</div>
                                </td>
                                <td class="px-6 py-4 font-bold text-primary-900">
                                    {{ $workshop->price == 0 ? 'Gratis' : 'Rp ' . number_format($workshop->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <div class="flex items-center gap-1.5 font-bold text-slate-800">
                                        <span>{{ $workshop->paid_registrations }} lunas</span>
                                        <span class="text-slate-300">/</span>
                                        <span>{{ $workshop->total_registrations }} daftar</span>
                                        <span class="text-slate-300">/</span>
                                        <span class="text-slate-500">kuota {{ $workshop->quota }}</span>
                                    </div>
                                    @php
                                        $sisa = max(0, $workshop->quota - $workshop->paid_registrations);
                                    @endphp
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold {{ $sisa > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        Sisa: {{ $sisa }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('panitia.workshops.registrants', $workshop->id) }}" class="p-2 text-slate-500 hover:text-primary-900 hover:bg-slate-100 rounded-lg transition" title="Lihat Pendaftar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </a>
                                        <a href="{{ route('panitia.workshops.edit', $workshop->id) }}" class="p-2 text-slate-500 hover:text-primary-900 hover:bg-slate-100 rounded-lg transition" title="Edit Workshop">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <button
                                            type="button"
                                            @click="deleteModalOpen = true; deleteActionUrl = '{{ route('panitia.workshops.destroy', $workshop->id) }}'; deleteTitle = '{{ addslashes($workshop->title) }}'"
                                            class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition"
                                            title="Hapus Workshop"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                    Tidak ada data workshop yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($workshops->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $workshops->links() }}
                </div>
            @endif

            <!-- Modal Konfirmasi Hapus -->
            <div
                x-show="deleteModalOpen"
                x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
            >
                <div @click.away="deleteModalOpen = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>

                    <h3 class="text-lg font-bold text-slate-900 text-center">Konfirmasi Hapus Workshop</h3>
                    <p class="text-sm text-slate-500 text-center mt-2">
                        Apakah Anda yakin ingin menghapus workshop <span class="font-bold text-slate-800" x-text="deleteTitle"></span>? Tindakan ini tidak dapat dibatalkan.
                    </p>

                    <form :action="deleteActionUrl" method="POST" class="mt-6 flex gap-3">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="deleteModalOpen = false" class="w-1/2 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" class="w-1/2 py-2.5 text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition shadow-xs">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>