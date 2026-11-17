<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                    <a href="{{ route('panitia.workshops.index') }}" class="hover:text-primary-900">Kelola Workshop</a>
                    <span>/</span>
                    <span class="text-slate-600 font-medium">Daftar Pendaftar</span>
                </nav>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pendaftar: {{ $workshop->title }}</h1>
                <p class="text-sm text-slate-500 mt-0.5">Pemateri: {{ $workshop->speaker }} &bull; Total: {{ $registrations->count() }} Pendaftar</p>
            </div>
            <a href="{{ route('panitia.workshops.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                &larr; Kembali ke Workshop
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Order ID</th>
                            <th class="px-6 py-4">Nama Peserta</th>
                            <th class="px-6 py-4">Email Peserta</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Kode Tiket</th>
                            <th class="px-6 py-4">Waktu Daftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($registrations as $index => $reg)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 text-xs font-bold text-slate-400">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs font-bold text-slate-800">
                                    {{ $reg->order_id }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    {{ $reg->user->name }}
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500">
                                    {{ $reg->user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    <x-status-badge :status="$reg->status" />
                                </td>
                                <td class="px-6 py-4 font-mono text-xs font-semibold {{ $reg->ticket_code ? 'text-primary-900 bg-primary-50 px-2.5 py-1 rounded-md inline-block my-2' : 'text-slate-400' }}">
                                    {{ $reg->ticket_code ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400">
                                    {{ $reg->created_at->translatedFormat('d M Y, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                    Belum ada peserta yang mendaftar untuk workshop ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>