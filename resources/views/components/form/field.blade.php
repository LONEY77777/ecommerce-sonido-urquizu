@props(['label', 'name', 'hint' => null, 'required' => false])

<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-900">
        {{ $label }}
        @if ($required)
            <span class="text-rose-600" aria-hidden="true">*</span>
        @endif
    </label>

    <div class="mt-2">
        {{ $slot }}
    </div>

    @error($name)
        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
    @enderror

    @if ($hint)
        <p class="mt-1.5 text-sm text-slate-500">{{ $hint }}</p>
    @endif
</div>
