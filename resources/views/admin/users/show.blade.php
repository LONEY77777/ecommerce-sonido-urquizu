<x-layouts.admin :title="$user->name" :heading="$user->name" :subheading="$user->email">
    <x-slot:actions>
        <a href="{{ route('admin.users.index') }}"
           class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Back to users
        </a>

        @unless ($user->isAdmin())
            <x-confirm-delete :id="'delete-user-'.$user->id"
                              :action="route('admin.users.destroy', $user)"
                              title="Delete this user?"
                              :message="$user->name.'’s account and order history will be permanently removed.'"
                              confirm-label="Delete user"
                              trigger-label="Delete user"
                              class="border border-rose-200 bg-white px-4 py-2.5" />
        @endunless
    </x-slot:actions>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <div class="rounded-xl border border-slate-200 bg-white p-6">
                <div class="flex items-center gap-4">
                    <span class="flex size-14 shrink-0 items-center justify-center rounded-full bg-brand-100 text-xl font-bold text-brand-800">
                        {{ Str::of($user->name)->substr(0, 1)->upper() }}
                    </span>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                        <p class="mt-0.5 truncate text-sm text-slate-500">{{ $user->email }}</p>
                    </div>
                </div>

                <dl class="mt-6 space-y-4 border-t border-slate-200 pt-6 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Role</dt>
                        <dd>
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $user->isAdmin() ? 'bg-brand-100 text-brand-800 ring-brand-600/20' : 'bg-slate-100 text-slate-700 ring-slate-600/20' }}">
                                {{ $user->role->label() }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Joined</dt>
                        <dd class="font-medium text-slate-900">{{ $user->created_at->format('j F Y') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Total orders</dt>
                        <dd class="font-medium tabular-nums text-slate-900">{{ $orders->total() }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <h2 class="border-b border-slate-200 px-6 py-4 text-base font-semibold text-slate-900">Order history</h2>

                @if ($orders->isEmpty())
                    <p class="px-6 py-12 text-center text-sm text-slate-500">This user has not placed an order yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left font-semibold text-slate-900">Order</th>
                                    <th scope="col" class="px-6 py-3 text-left font-semibold text-slate-900">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left font-semibold text-slate-900">Items</th>
                                    <th scope="col" class="px-6 py-3 text-left font-semibold text-slate-900">Total</th>
                                    <th scope="col" class="px-6 py-3 text-left font-semibold text-slate-900">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($orders as $order)
                                    <tr class="hover:bg-slate-50">
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-brand-700 hover:text-brand-800">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-slate-600">{{ $order->created_at->format('j M Y') }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 tabular-nums text-slate-600">{{ $order->items_count }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 font-semibold text-slate-900">
                                            <x-price :amount="$order->total_amount" />
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <x-status-badge :status="$order->status" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="border-t border-slate-200 px-6 py-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>
