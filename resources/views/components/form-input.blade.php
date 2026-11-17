@props(['label', 'name', 'type' => 'text', 'value' => '', 'required' => false, 'placeholder' => ''])

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold text-slate-700 mb-1">
        {{ $label }}
        @if($required)
            <span class="text-rose-500">*</span>
        @endif
    </label>

    @if($type === 'textarea')
        <textarea
            id="{{ $name }}"
            name="{{ $name }}"
            rows="4"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full rounded-xl border-slate-300 shadow-xs focus:border-primary-500 focus:ring-primary-500 text-sm transition']) }}
        >{{ old($name, $value) }}</textarea>
    @else
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full rounded-xl border-slate-300 shadow-xs focus:border-primary-500 focus:ring-primary-500 text-sm transition']) }}
        />
    @endif

    @error($name)
        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
    @enderror
</div>