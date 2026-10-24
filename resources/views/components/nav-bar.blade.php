@props(['cartCount' => 0])

<header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
    <nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8" aria-label="Main">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5">
            <span class="flex size-9 items-center justify-center rounded-lg bg-brand-600 text-white">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 19l7-7 3 3-7 7-3-3z" />
                    <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z" />
                    <path d="M2 2l7.586 7.586" />
                    <circle cx="11" cy="11" r="2" />
                </svg>
            </span>
            <span class="text-lg font-bold tracking-tight text-slate-900">{{ config('store.name') }}</span>
        </a>

        <div class="hidden items-center gap-1 md:flex">
            <x-nav-link :href="route('home')" :active="request()->routeIs('home')">Home</x-nav-link>
            <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">Products</x-nav-link>

            @auth
                <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">My Orders</x-nav-link>

                @if (auth()->user()->isAdmin())
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">Dashboard</x-nav-link>
                @endif
            @endauth
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('cart.index') }}"
               class="relative inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 hover:text-slate-900">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" />
                </svg>
                <span class="hidden sm:inline">Cart</span>
                @if ($cartCount > 0)
                    <span class="absolute -right-0.5 -top-0.5 inline-flex min-w-5 items-center justify-center rounded-full bg-brand-600 px-1.5 py-0.5 text-[11px] font-semibold leading-none text-white">
                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                        <span class="sr-only">items in cart</span>
                    </span>
                @endif
            </a>

            @guest
                <a href="{{ route('login') }}" class="hidden rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 sm:inline-block">Login</a>
                <a href="{{ route('register') }}" class="rounded-lg bg-brand-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">Register</a>
            @endguest

            @auth
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button type="submit" class="rounded-lg border border-slate-300 px-3.5 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Logout
                    </button>
                </form>
            @endauth

            <button type="button" data-nav-toggle aria-expanded="false" aria-controls="mobile-menu"
                    class="inline-flex items-center rounded-lg p-2 text-slate-700 transition hover:bg-slate-100 md:hidden">
                <span class="sr-only">Toggle navigation</span>
                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>
        </div>
    </nav>

    <div id="mobile-menu" class="hidden border-t border-slate-200 px-4 py-3 md:hidden">
        <div class="flex flex-col gap-1">
            <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="w-full">Home</x-nav-link>
            <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="w-full">Products</x-nav-link>

            @auth
                <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="w-full">My Orders</x-nav-link>

                @if (auth()->user()->isAdmin())
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="w-full">Dashboard</x-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-left text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Logout
                    </button>
                </form>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100">Login</a>
            @endguest
        </div>
    </div>
</header>
