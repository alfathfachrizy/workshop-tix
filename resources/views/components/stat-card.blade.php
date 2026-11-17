@props(['title', 'value', 'icon' => null, 'subtext' => null, 'color' => 'primary'])

@php
    $colorMap = [
        'primary' => 'bg-primary-50 text-primary-900 border-primary-200',
        'accent' => 'bg-amber-50 text-amber-700 border-amber-200',
        'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'rose' => 'bg-rose-50 text-rose-700 border-rose-200',
        'purple' => 'bg-purple-50 text-purple-700 border-purple-200',
    ];
    $iconBg = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $title }}</p>
            <p class="mt-2 text-3xl font-extrabold text-slate-900 tracking-tight">{{ $value }}</p>
            @if($subtext)
                <p class="mt-1 text-xs text-slate-500">{{ $subtext }}</p>
            @endif
        </div>
        @if($icon)
            <div class="w-12 h-12 rounded-xl border flex items-center justify-center {{ $iconBg }}">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>