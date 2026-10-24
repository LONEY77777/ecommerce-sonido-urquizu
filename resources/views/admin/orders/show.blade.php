<x-layouts.admin :title="'Order '.$order->order_number"
                 :heading="$order->order_number"
                 :subheading="'Placed '.$order->created_at->format('j F Y').' at '.$order->created_at->format('g:ia')">
    <x-slot:actions>
        <a href="{{ route('admin.orders.index') }}"
           class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Back to orders
        </a>
    </x-slot:actions>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Items --}}
        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <h2 class="border-b border-slate-200 px-6 py-4 text-base font-semibold text-slate-900">
                    Ordered products
                </h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left font-semibold text-slate-900">Product</th>
                                <th scope="col" class="px-6 py-3 text-right font-semibold text-slate-900">Price</th>
                                <th scope="col" class="px-6 py-3 text-right font-semibold text-slate-900">Qty</th>
                                <th scope="col" class="px-6 py-3 text-right font-semibold text-slate-900">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $item->product?->imageUrl() ?? asset('images/product-placeholder.svg') }}"
                                                 alt="" aria-hidden="true"
                                                 class="size-11 shrink-0 rounded-lg border border-slate-200 object-cover">
                                            <div class="min-w-0">
                                                <p class="font-medium text-slate-900">{{ $item->product_name }}</p>
                                                @if ($item->product)
                                                    <a href="{{ route('admin.products.show', $item->product) }}"
                                                       class="mt-0.5 text-xs text-brand-700 hover:text-brand-800">
                                                        View product
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-slate-600">
                                        <x-price :amount="$item->price" />
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right tabular-nums text-slate-600">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right font-semibold text-slate-900">
                                        <x-price :amount="$item->subtotal()" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t border-slate-200 bg-slate-50">
                            <tr>
                                <th scope="row" colspan="3" class="px-6 py-4 text-right text-base font-semibold text-slate-900">
                                    Order total
                                </th>
                                <td class="px-6 py-4 text-right text-lg font-bold text-slate-900">
                                    <x-price :amount="$order->total_amount" />
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Customer & status --}}
        <aside class="flex flex-col gap-6 lg:col-span-1">
            <div class="rounded-xl border border-slate-200 bg-white p-6">
                <h2 class="text-base font-semibold text-slate-900">Order status</h2>

                <div class="mt-4">
                    <x-status-badge :status="$order->status" class="text-sm" />
                </div>

                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-5">
                    @csrf
                    @method('PATCH')

                    <x-form.field label="Change status to" name="status">
                        <x-form.select name="status" :value="$order->status->value"
                                       :options="collect($statuses)->mapWithKeys(fn ($status) => [$status->value => $status->label()])->all()" />
                    </x-form.field>

                    <button type="submit"
                            class="mt-4 w-full rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                        Update status
                    </button>
                </form>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6">
                <h2 class="text-base font-semibold text-slate-900">Customer information</h2>

                <dl class="mt-4 space-y-3 text-sm">
                    <div>
                        <dt class="text-slate-500">Name on order</dt>
                        <dd class="mt-0.5 font-medium text-slate-900">{{ $order->customer_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Account</dt>
                        <dd class="mt-0.5">
                            <a href="{{ route('admin.users.show', $order->user) }}" class="font-medium text-brand-700 hover:text-brand-800">
                                {{ $order->user->name }}
                            </a>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $order->user->email }}</p>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Phone</dt>
                        <dd class="mt-0.5 font-medium text-slate-900">{{ $order->phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Delivery address</dt>
                        <dd class="mt-0.5 whitespace-pre-line font-medium text-slate-900">{{ $order->address }}</dd>
                    </div>
                    @if ($order->notes)
                        <div>
                            <dt class="text-slate-500">Notes</dt>
                            <dd class="mt-0.5 whitespace-pre-line text-slate-700">{{ $order->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </aside>
    </div>
</x-layouts.admin>
