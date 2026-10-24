@props(['amount', 'decimals' => 2])

<span {{ $attributes->merge(['class' => 'tabular-nums']) }}>{{ config('store.currency_symbol') }}{{ number_format((float) $amount, $decimals) }}</span>
