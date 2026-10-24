@props([
    'action',
    'id',
    'title' => 'Are you sure?',
    'message' => 'This action cannot be undone.',
    'confirmLabel' => 'Delete',
    'triggerLabel' => 'Delete',
])

<button type="button" data-confirm-open="{{ $id }}"
        {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-50']) }}>
    {{ $triggerLabel }}
</button>

<dialog id="{{ $id }}"
        class="w-full max-w-md rounded-xl border border-slate-200 p-0 shadow-xl backdrop:bg-slate-900/40">
    <div class="p-6">
        <div class="flex items-start gap-4">
            <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-700">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    <path d="M12 9v4M12 17h.01" />
                </svg>
            </span>

            <div class="min-w-0">
                <h2 class="text-base font-semibold text-slate-900">{{ $title }}</h2>
                <p class="mt-1.5 text-sm text-slate-600">{{ $message }}</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" data-confirm-close
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Cancel
            </button>

            <form method="POST" action="{{ $action }}">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                    {{ $confirmLabel }}
                </button>
            </form>
        </div>
    </div>
</dialog>
