@props(['title' => null, 'description' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title.' — '.config('store.name') : config('store.name') }}</title>
    <meta name="description" content="{{ $description ?? 'StationeryHub — pens, paper, art supplies and desk essentials, delivered.' }}">

    <link rel="icon" href="{{ asset('images/product-placeholder.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full flex-col">
    <x-nav-bar />

    <main class="flex-1">
        <x-flash class="mx-auto mt-6 max-w-7xl px-4 sm:px-6 lg:px-8" />

        {{ $slot }}
    </main>

    <x-site-footer />
</body>
</html>
