@props(['status'])

@php
    $normalized = strtolower($status ?? 'pending');
    $config = match($normalized) {
        'paid' => [
            'bg' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'dot' => 'bg-emerald-500',
            'label' => 'Lunas / Paid',
        ],
        'pending' => [
            'bg' => 'bg-amber-100 text-amber-800 border-amber-200',
            'dot' => 'bg-amber-500',
            'label' => 'Menunggu Pembayaran',
        ],
        'failed' => [
            'bg' => 'bg-rose-100 text-rose-800 border-rose-200',
            'dot' => 'bg-rose-500',
            'label' => 'Gagal / Failed',
        ],
        'expired' => [
            'bg' => 'bg-slate-100 text-slate-800 border-slate-300',
            'dot' => 'bg-slate-400',
            'label' => 'Kedaluwarsa',
        ],
        default => [
            'bg' => 'bg-slate-100 text-slate-700 border-slate-200',
            'dot' => 'bg-slate-400',
            'label' => ucfirst($normalized),
        ],
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border ' . $config['bg']]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
    {{ $config['label'] }}
</span>