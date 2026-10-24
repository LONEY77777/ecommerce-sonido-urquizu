@props(['active' => false, 'href' => '#'])

<a href="{{ $href }}"
   @if ($active) aria-current="page" @endif
   {{ $attributes->class([
       'rounded-lg px-3 py-2 text-sm font-medium transition',
       'bg-brand-50 text-brand-700' => $active,
       'text-slate-700 hover:bg-slate-100 hover:text-slate-900' => ! $active,
   ]) }}>
    {{ $slot }}
</a>
