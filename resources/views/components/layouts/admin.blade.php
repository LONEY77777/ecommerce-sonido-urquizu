@props(['title' => null, 'heading' => null, 'subheading' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title.' — Admin' : 'Admin' }} — {{ config('store.name') }}</title>

    <link rel="icon" href="{{ asset('images/product-placeholder.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-slate-100">
    <div class="lg:flex">
        {{-- Sidebar --}}
        <aside class="hidden w-64 shrink-0 border-r border-slate-200 bg-white lg:fixed lg:inset-y-0 lg:flex lg:flex-col">
            <div class="flex h-16 shrink-0 items-center gap-2.5 border-b border-slate-200 px-6">
                <span class="flex size-8 items-center justify-center rounded-lg bg-brand-600 text-white">
                    <svg class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 19l7-7 3 3-7 7-3-3z" /><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z" />
                        <path d="M2 2l7.586 7.586" /><circle cx="11" cy="11" r="2" />
                    </svg>
                </span>
                <span class="text-base font-bold tracking-tight text-slate-900">Admin panel</span>
            </div>

            <nav class="flex flex-1 flex-col gap-1 overflow-y-auto p-4" aria-label="Admin">
                <x-admin.sidebar-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    <x-slot:icon>
                        <path d="M3 13h8V3H3v10zM13 21h8V11h-8v10zM13 3v6h8V3h-8zM3 21h8v-6H3v6z" />
                    </x-slot:icon>
                    Dashboard
                </x-admin.sidebar-link>

                <x-admin.sidebar-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                    <x-slot:icon>
                        <path d="M20 7l-8-4-8 4v10l8 4 8-4V7z" /><path d="M4 7l8 4 8-4M12 11v10" />
                    </x-slot:icon>
                    Products
                </x-admin.sidebar-link>

                <x-admin.sidebar-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                    <x-slot:icon>
                        <path d="M6 2h12l2 5H4l2-5z" /><path d="M4 7v13h16V7" /><path d="M9 11h6" />
                    </x-slot:icon>
                    Orders
                </x-admin.sidebar-link>

                <x-admin.sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    <x-slot:icon>
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" /><circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                    </x-slot:icon>
                    Users
                </x-admin.sidebar-link>

                <div class="mt-auto border-t border-slate-200 pt-4">
                    <x-admin.sidebar-link :href="route('home')" :active="false">
                        <x-slot:icon>
                            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" /><path d="M9 22V12h6v10" />
                        </x-slot:icon>
                        View storefront
                    </x-admin.sidebar-link>
                </div>
            </nav>
        </aside>

        {{-- Main column --}}
        <div class="flex min-w-0 flex-1 flex-col lg:pl-64">
            <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-slate-200 bg-white px-4 sm:px-6 lg:px-8">
                <button type="button" data-nav-toggle aria-expanded="false" aria-controls="mobile-menu"
                        class="inline-flex items-center rounded-lg p-2 text-slate-700 transition hover:bg-slate-100 lg:hidden">
                    <span class="sr-only">Toggle navigation</span>
                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                </button>

                <p class="truncate text-sm font-semibold text-slate-900 lg:hidden">Admin panel</p>

                <div class="ml-auto flex items-center gap-3">
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                    </div>

                    <span class="flex size-9 items-center justify-center rounded-full bg-brand-100 text-sm font-bold text-brand-800">
                        {{ Str::of(auth()->user()->name)->substr(0, 1)->upper() }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg border border-slate-300 px-3.5 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            {{-- Mobile nav --}}
            <div id="mobile-menu" class="hidden border-b border-slate-200 bg-white px-4 py-3 lg:hidden">
                <nav class="flex flex-col gap-1" aria-label="Admin mobile">
                    <x-admin.sidebar-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        <x-slot:icon><path d="M3 13h8V3H3v10zM13 21h8V11h-8v10zM13 3v6h8V3h-8zM3 21h8v-6H3v6z" /></x-slot:icon>
                        Dashboard
                    </x-admin.sidebar-link>
                    <x-admin.sidebar-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                        <x-slot:icon><path d="M20 7l-8-4-8 4v10l8 4 8-4V7z" /><path d="M4 7l8 4 8-4M12 11v10" /></x-slot:icon>
                        Products
                    </x-admin.sidebar-link>
                    <x-admin.sidebar-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                        <x-slot:icon><path d="M6 2h12l2 5H4l2-5z" /><path d="M4 7v13h16V7" /><path d="M9 11h6" /></x-slot:icon>
                        Orders
                    </x-admin.sidebar-link>
                    <x-admin.sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        <x-slot:icon><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" /><circle cx="9" cy="7" r="4" /></x-slot:icon>
                        Users
                    </x-admin.sidebar-link>
                    <x-admin.sidebar-link :href="route('home')" :active="false">
                        <x-slot:icon><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" /><path d="M9 22V12h6v10" /></x-slot:icon>
                        View storefront
                    </x-admin.sidebar-link>
                </nav>
            </div>

            <main class="flex-1 px-4 py-8 sm:px-6 lg:px-8">
                @if ($heading)
                    <header class="mb-6 flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $heading }}</h1>
                            @if ($subheading)
                                <p class="mt-1.5 text-sm text-slate-600">{{ $subheading }}</p>
                            @endif
                        </div>

                        @isset($actions)
                            <div class="flex flex-wrap gap-3">{{ $actions }}</div>
                        @endisset
                    </header>
                @endif

                <x-flash class="mb-6" />

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
