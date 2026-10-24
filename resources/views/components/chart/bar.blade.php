@props([
    'title',
    'subtitle' => null,
    'series' => [],
    'prefix' => '',
    'valueLabel' => 'Value',
    'keyLabel' => 'Day',
])

@php
    // Geometry. The viewBox keeps the chart responsive without distorting text.
    $width = 720;
    $height = 260;
    $padding = ['top' => 16, 'right' => 8, 'bottom' => 30, 'left' => 56];

    $plotWidth = $width - $padding['left'] - $padding['right'];
    $plotHeight = $height - $padding['top'] - $padding['bottom'];
    $baseline = $padding['top'] + $plotHeight;

    $count = max(count($series), 1);
    $max = max(collect($series)->max() ?? 0, 1);

    // Round the top tick up to something readable rather than the raw maximum.
    $magnitude = 10 ** max(floor(log10($max)), 0);
    $axisMax = ceil($max / $magnitude) * $magnitude;

    $slot_ = $plotWidth / $count;
    // A 2px surface gap between adjacent fills, capped so bars stay thin.
    $barWidth = min($slot_ - 2, 44);
    $radius = min(4, $barWidth / 2);

    $ticks = [0, $axisMax / 2, $axisMax];

    // Label every other column when there are many, so ticks never collide.
    $labelEvery = $count > 8 ? 2 : 1;
@endphp

<figure {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white p-6']) }}>
    <figcaption>
        <h2 class="text-base font-semibold text-slate-900">{{ $title }}</h2>
        @if ($subtitle)
            <p class="mt-1 text-sm text-slate-600">{{ $subtitle }}</p>
        @endif
    </figcaption>

    @if (collect($series)->sum() <= 0)
        <p class="mt-8 rounded-lg bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">
            No {{ Str::lower($valueLabel) }} recorded in this period yet.
        </p>
    @else
        <div class="mt-5 overflow-x-auto">
            <svg viewBox="0 0 {{ $width }} {{ $height }}" class="h-64 w-full min-w-[520px]" role="img"
                 aria-label="{{ $title }}">
                {{-- Gridlines and y-axis ticks --}}
                @foreach ($ticks as $tick)
                    @php $y = $baseline - ($axisMax > 0 ? ($tick / $axisMax) * $plotHeight : 0); @endphp
                    <line x1="{{ $padding['left'] }}" y1="{{ $y }}" x2="{{ $width - $padding['right'] }}" y2="{{ $y }}"
                          stroke="{{ $tick == 0 ? 'var(--color-viz-axis)' : 'var(--color-viz-grid)' }}" stroke-width="1" />
                    <text x="{{ $padding['left'] - 10 }}" y="{{ $y + 4 }}" text-anchor="end"
                          font-size="11" fill="var(--color-viz-muted)" style="font-variant-numeric: tabular-nums">
                        {{ $prefix }}{{ $tick >= 1000 ? round($tick / 1000, 1).'k' : round($tick) }}
                    </text>
                @endforeach

                {{-- Bars --}}
                @foreach ($series as $label => $value)
                    @php
                        $index = $loop->index;
                        $barHeight = $axisMax > 0 ? ($value / $axisMax) * $plotHeight : 0;
                        $x = $padding['left'] + ($index * $slot_) + (($slot_ - $barWidth) / 2);
                        $y = $baseline - $barHeight;
                        $centre = $x + ($barWidth / 2);
                    @endphp

                    <g class="group">
                        {{-- A generous invisible hit target, larger than the mark itself. --}}
                        <rect x="{{ $padding['left'] + ($index * $slot_) }}" y="{{ $padding['top'] }}"
                              width="{{ $slot_ }}" height="{{ $plotHeight }}" fill="transparent">
                            <title>{{ $label }}: {{ $prefix }}{{ number_format((float) $value, 2) }}</title>
                        </rect>

                        @if ($barHeight > 0)
                            <rect x="{{ $x }}" y="{{ $y }}" width="{{ $barWidth }}" height="{{ $barHeight }}"
                                  rx="{{ $radius }}" fill="var(--color-viz-series)"
                                  class="transition-opacity group-hover:opacity-80" />
                        @else
                            {{-- Zero still gets a visible baseline tick so the day is not simply missing. --}}
                            <rect x="{{ $x }}" y="{{ $baseline - 2 }}" width="{{ $barWidth }}" height="2"
                                  fill="var(--color-viz-axis)" />
                        @endif

                        {{-- Direct label on hover only, never a number on every bar. --}}
                        <text x="{{ $centre }}" y="{{ max($y - 8, 12) }}" text-anchor="middle" font-size="11"
                              font-weight="600" fill="#0b0b0b" opacity="0"
                              class="pointer-events-none transition-opacity group-hover:opacity-100"
                              style="font-variant-numeric: tabular-nums">
                            {{ $prefix }}{{ number_format((float) $value, 0) }}
                        </text>

                        @if ($index % $labelEvery === 0)
                            <text x="{{ $centre }}" y="{{ $height - 10 }}" text-anchor="middle" font-size="11"
                                  fill="var(--color-viz-muted)">{{ $label }}</text>
                        @endif
                    </g>
                @endforeach
            </svg>
        </div>

        {{-- The table view keeps the data readable without relying on the plot. --}}
        <details class="mt-4">
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
                                <td class="py-2 text-right tabular-nums text-slate-900">
                                    {{ $prefix }}{{ number_format((float) $value, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </details>
    @endif
</figure>
