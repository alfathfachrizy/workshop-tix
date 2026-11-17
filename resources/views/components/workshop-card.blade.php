@props(['workshop'])

@php
    $remainingQuota = $workshop->remaining_quota ?? max(0, $workshop->quota - ($workshop->paid_registrations_count ?? $workshop->registrations()->where('status', 'paid')->count()));
    $isFull = $remainingQuota <= 0;
    $formattedPrice = $workshop->price == 0 ? 'Gratis' : 'Rp ' . number_format($workshop->price, 0, ',', '.');
@endphp

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
    <div>
        <div class="p-6">
            <div class="flex items-center justify-between gap-2 mb-3">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-primary-50 text-primary-800 border border-primary-200">
                    Workshop IT
                </span>

                @if($isFull)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                        Penuh
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Sisa Kuota: {{ $remainingQuota }}
                    </span>
                @endif
            </div>

            <h3 class="text-lg font-bold text-slate-900 group-hover:text-primary-900 transition-colors line-clamp-2">
                <a href="{{ route('workshops.show', $workshop->slug) }}">
                    {{ $workshop->title }}
                </a>
            </h3>

            <p class="mt-2 text-sm text-slate-600 line-clamp-3">
                {{ $workshop->description }}
            </p>

            <div class="mt-5 space-y-2 border-t border-slate-100 pt-4 text-xs text-slate-600">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium text-slate-700">{{ $workshop->speaker }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($workshop->starts_at)->translatedFormat('d M Y, H:i') }} WIB</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="truncate">{{ $workshop->location }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
        <div>
            <span class="text-xs text-slate-500 block">Biaya Investasi</span>
            <span class="text-base font-extrabold text-primary-900">{{ $formattedPrice }}</span>
        </div>

        <a href="{{ route('workshops.show', $workshop->slug) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold {{ $isFull ? 'bg-slate-200 text-slate-500 hover:bg-slate-300' : 'bg-primary-900 text-white hover:bg-primary-800 shadow-xs' }} transition-colors">
            <span>Detail</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
</div>