@props(['title', 'message' => null])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center']) }}>
    <svg class="mx-auto size-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M20 7l-8-4-8 4v10l8 4 8-4V7z" /><path d="M4 7l8 4 8-4M12 11v10" />
    </svg>
    <h3 class="mt-4 text-base font-semibold text-slate-900">{{ $title }}</h3>

    @if ($message)
        <p class="mt-1.5 text-sm text-slate-600">{{ $message }}</p>
    @endif

    @if (! $slot->isEmpty())
        <div class="mt-6">{{ $slot }}</div>
    @endif
</div>
