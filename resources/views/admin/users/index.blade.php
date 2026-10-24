<x-layouts.admin title="Users" heading="Users"
                 subheading="{{ $users->total() }} {{ Str::plural('account', $users->total()) }} registered.">
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6 flex flex-wrap items-center gap-3">
        <div class="min-w-64 flex-1">
            <label for="search" class="sr-only">Search users</label>
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" /><path d="M21 21l-4.3-4.3" />
                </svg>
                <input type="search" name="search" id="search" value="{{ $search }}"
                       placeholder="Search by name or email" class="field-input pl-9">
            </div>
        </div>

        <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
            Search
        </button>

        @if ($search)
            <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Clear
            </a>
        @endif
    </form>

    @if ($users->isEmpty())
        <x-empty-state title="No users found" message="No account matches that search." />
    @else
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">User</th>
                            <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Role</th>
                            <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Orders</th>
                            <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Joined</th>
                            <th scope="col" class="px-6 py-3.5 text-right font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($users as $user)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-brand-100 text-sm font-bold text-brand-800">
                                            {{ Str::of($user->name)->substr(0, 1)->upper() }}
                                        </span>
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="font-medium text-slate-900 hover:text-brand-700">{{ $user->name }}</a>
                                            <p class="mt-0.5 text-xs text-slate-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $user->isAdmin() ? 'bg-brand-100 text-brand-800 ring-brand-600/20' : 'bg-slate-100 text-slate-700 ring-slate-600/20' }}">
                                        {{ $user->role->label() }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 tabular-nums text-slate-600">{{ $user->orders_count }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-slate-600">{{ $user->created_at->format('j M Y') }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                           class="rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                            View<span class="sr-only">, {{ $user->name }}</span>
                                        </a>

                                        @unless ($user->isAdmin())
                                            <x-confirm-delete :id="'delete-user-'.$user->id"
                                                              :action="route('admin.users.destroy', $user)"
                                                              title="Delete this user?"
                                                              :message="$user->name.'’s account and order history will be permanently removed.'"
                                                              confirm-label="Delete user" />
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
</x-layouts.admin>
