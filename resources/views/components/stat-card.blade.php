@props(['label', 'value', 'caption' => null, 'icon' => null])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white p-6']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <p class="text-sm font-medium text-slate-600">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $value }}</p>

            @if ($caption)
                <p class="mt-1.5 text-sm text-slate-500">{{ $caption }}</p>
            @endif
        </div>

        @if ($icon)
            <span class="flex size-11 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    {{ $icon }}
                </svg>
            </span>
        @endif
    </div>
</div>
