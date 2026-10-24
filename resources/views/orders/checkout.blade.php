<x-layouts.app title="Checkout">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900">Checkout</h1>
        <p class="mt-1.5 text-sm text-slate-600">Tell us where to send your order.</p>

        <form method="POST" action="{{ route('orders.store') }}" class="mt-8 grid gap-8 lg:grid-cols-3">
            @csrf

            {{-- Delivery details --}}
            <div class="lg:col-span-2">
                <div class="rounded-xl border border-slate-200 bg-white p-6">
                    <h2 class="text-base font-semibold text-slate-900">Delivery details</h2>

                    <div class="mt-6 grid gap-6 sm:grid-cols-2">
                        <x-form.field label="Full name" name="customer_name" :required="true">
                            <x-form.input name="customer_name" :value="auth()->user()->name" autocomplete="name" />
                        </x-form.field>

                        <x-form.field label="Phone number" name="phone" :required="true">
                            <x-form.input name="phone" type="tel" autocomplete="tel" placeholder="+880 1712 345678" />
                        </x-form.field>

                        <x-form.field label="Delivery address" name="address" :required="true" class="sm:col-span-2">
                            <x-form.textarea name="address" rows="3" autocomplete="street-address"
                                             placeholder="House, road, area, city and postcode" />
                        </x-form.field>

                        <x-form.field label="Order notes" name="notes" class="sm:col-span-2"
                                      hint="Optional — delivery instructions, gift messages, anything we should know.">
                            <x-form.textarea name="notes" rows="3" placeholder="Please deliver after 5pm." />
                        </x-form.field>
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <aside class="lg:col-span-1">
                <div class="sticky top-24 rounded-xl border border-slate-200 bg-white p-6">
                    <h2 class="text-base font-semibold text-slate-900">Your order</h2>

                    <ul role="list" class="mt-5 divide-y divide-slate-100">
                        @foreach ($cartItems as $item)
                            <li class="flex items-start gap-3 py-3">
                                <img src="{{ $item->product->imageUrl() }}" alt="" aria-hidden="true"
                                     class="size-12 shrink-0 rounded-lg border border-slate-200 object-cover">

                                <div class="min-w-0 flex-1">
                                    <p class="line-clamp-2 text-sm font-medium text-slate-900">{{ $item->product->name }}</p>
                                    <p class="mt-0.5 text-sm text-slate-500">
                                        <x-price :amount="$item->product->price" /> &times; {{ $item->quantity }}
                                    </p>
                                </div>

                                <p class="shrink-0 text-sm font-semibold text-slate-900">
                                    <x-price :amount="$item->subtotal()" />
                                </p>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-5 space-y-3 border-t border-slate-200 pt-5 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Subtotal</span>
                            <span class="font-medium text-slate-900"><x-price :amount="$total" /></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Delivery</span>
                            <span class="font-medium text-emerald-700">Free</span>
                        </div>
                    </div>

                    <div class="mt-5 flex items-baseline justify-between border-t border-slate-200 pt-5">
                        <span class="text-base font-semibold text-slate-900">Total</span>
                        <span class="text-2xl font-bold text-slate-900"><x-price :amount="$total" /></span>
                    </div>

                    <button type="submit"
                            class="mt-6 w-full rounded-lg bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                        Place order
                    </button>

                    <a href="{{ route('cart.index') }}"
                       class="mt-3 block text-center text-sm font-semibold text-slate-600 hover:text-slate-900">
                        Back to cart
                    </a>
                </div>
            </aside>
        </form>
    </div>
</x-layouts.app>
