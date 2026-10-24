<footer class="mt-16 border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 md:grid-cols-3">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="flex size-9 items-center justify-center rounded-lg bg-brand-600 text-white">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 19l7-7 3 3-7 7-3-3z" />
                            <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z" />
                            <path d="M2 2l7.586 7.586" />
                            <circle cx="11" cy="11" r="2" />
                        </svg>
                    </span>
                    <span class="text-lg font-bold tracking-tight text-slate-900">{{ config('store.name') }}</span>
                </div>
                <p class="mt-4 max-w-sm text-sm leading-6 text-slate-600">
                    Pens, paper, art supplies and desk essentials chosen for people who care what they write with.
                    Ordered today, packed tomorrow.
                </p>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-slate-900">Contact</h2>
                <ul class="mt-4 space-y-3 text-sm text-slate-600">
                    <li class="flex items-start gap-2.5">
                        <svg class="mt-0.5 size-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 4h16v16H4z" /><path d="M22 6l-10 7L2 6" />
                        </svg>
                        <a href="mailto:{{ config('store.contact.email') }}" class="hover:text-brand-700">{{ config('store.contact.email') }}</a>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <svg class="mt-0.5 size-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.79 19.79 0 012.12 4.18 2 2 0 014.11 2h3a2 2 0 012 1.72c.13.96.36 1.9.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0122 16.92z" />
                        </svg>
                        <a href="tel:{{ config('store.contact.phone') }}" class="hover:text-brand-700">{{ config('store.contact.phone') }}</a>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <svg class="mt-0.5 size-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" /><circle cx="12" cy="10" r="3" />
                        </svg>
                        <span>{{ config('store.contact.address') }}</span>
                    </li>
                </ul>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-slate-900">Follow us</h2>
                <div class="mt-4 flex gap-3">
                    <a href="{{ config('store.social.facebook') }}" target="_blank" rel="noopener noreferrer"
                       class="flex size-10 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700">
                        <span class="sr-only">Facebook</span>
                        <svg class="size-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M22 12a10 10 0 10-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.45 2.89h-2.33v6.99A10 10 0 0022 12z" />
                        </svg>
                    </a>
                    <a href="{{ config('store.social.instagram') }}" target="_blank" rel="noopener noreferrer"
                       class="flex size-10 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700">
                        <span class="sr-only">Instagram</span>
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <rect x="2" y="2" width="20" height="20" rx="5" /><circle cx="12" cy="12" r="4" /><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
                        </svg>
                    </a>
                    <a href="{{ config('store.social.twitter') }}" target="_blank" rel="noopener noreferrer"
                       class="flex size-10 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700">
                        <span class="sr-only">X</span>
                        <svg class="size-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M18.24 2H21l-6.55 7.49L22.5 22h-6.3l-4.93-6.44L5.63 22H2.87l7.02-8.02L1.9 2h6.46l4.45 5.89L18.24 2zm-1.1 18.32h1.53L7.01 3.6H5.36l11.78 16.72z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-10 border-t border-slate-200 pt-6">
            <p class="text-sm text-slate-500">&copy; {{ now()->year }} {{ config('store.name') }}. All rights reserved.</p>
        </div>
    </div>
</footer>
