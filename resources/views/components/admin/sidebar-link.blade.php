@props(['href', 'active' => false, 'icon' => null])

<a href="{{ $href }}"
   @if ($active) aria-current="page" @endif
   {{ $attributes->class([
       'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition',
       'bg-brand-50 text-brand-700' => $active,
       'text-slate-700 hover:bg-slate-100 hover:text-slate-900' => ! $active,
   ]) }}>
    @if ($icon)
        <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            {{ $icon }}
        </svg>
    @endif

    {{ $slot }}
</a>
