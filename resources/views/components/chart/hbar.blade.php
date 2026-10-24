@props([
    'title',
    'subtitle' => null,
    'series' => [],
    'keyLabel' => 'Status',
    'valueLabel' => 'Orders',
])

@php
    $max = max(collect($series)->max() ?? 0, 1);
    $total = collect($series)->sum();
@endphp

<figure {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white p-6']) }}>
    <figcaption>
        <h2 class="text-base font-semibold text-slate-900">{{ $title }}</h2>
        @if ($subtitle)
            <p class="mt-1 text-sm text-slate-600">{{ $subtitle }}</p>
        @endif
    </figcaption>

    @if ($total <= 0)
        <p class="mt-8 rounded-lg bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">
            No orders recorded yet.
        </p>
    @else
        {{-- Each row is direct-labelled, so identity never rests on colour. --}}
        <ul role="list" class="mt-5 space-y-4">
            @foreach ($series as $label => $value)
                <li class="group">
                    <div class="flex items-baseline justify-between gap-4">
                        <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                        <span class="text-sm font-semibold tabular-nums text-slate-900">
                            {{ number_format($value) }}
                            <span class="ml-1 font-normal text-slate-500">
                                {{ $total > 0 ? round(($value / $total) * 100) : 0 }}%
                            </span>
                        </span>
                    </div>

                    <div class="mt-2 h-2.5 w-full overflow-hidden rounded-full bg-slate-100"
                         title="{{ $label }}: {{ number_format($value) }} {{ Str::lower(Str::plural(Str::singular($valueLabel), $value)) }}">
                        <div class="h-full rounded-full bg-[var(--color-viz-series)] transition-opacity group-hover:opacity-80"
                             style="width: {{ $value > 0 ? max(($value / $max) * 100, 2) : 0 }}%"></div>
                    </div>
                </li>
            @endforeach
        </ul>

        <details class="mt-6">
            <summary class="cursor-pointer text-sm font-medium text-slate-600 hover:text-slate-900">
                View as table
            </summary>
            <div class="mt-3 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead>
                        <tr>
                            <th scope="col" class="py-2 pr-4 text-left font-semibold text-slate-900">{{ $keyLabel }}</th>
                            <th scope="col" class="py-2 text-right font-semibold text-slate-900">{{ $valueLabel }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($series as $label => $value)
                            <tr>
                                <td class="py-2 pr-4 text-slate-600">{{ $label }}</td>
                                <td class="py-2 text-right tabular-nums text-slate-900">{{ number_format($value) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </details>
    @endif
</figure>
