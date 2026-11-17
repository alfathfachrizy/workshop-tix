@props(['title' => 'Belum Ada Data', 'description' => null, 'action' => null, 'actionUrl' => null])

<div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center my-6">
    <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-4">
        {{ $icon ?? '' }}
        @if(!isset($icon))
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        @endif
    </div>

    <h3 class="text-base font-bold text-slate-800">{{ $title }}</h3>

    @if($description)
        <p class="mt-1 text-sm text-slate-500 max-w-sm mx-auto">{{ $description }}</p>
    @endif

    @if($action && $actionUrl)
        <div class="mt-6">
            <a href="{{ $actionUrl }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-primary-900 hover:bg-primary-800 rounded-xl shadow-xs transition-colors">
                {{ $action }}
            </a>
        </div>
    @endif

    {{ $slot ?? '' }}
</div>