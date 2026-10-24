@php
    $messages = collect([
        'success' => session('success'),
        'error' => session('error'),
        'status' => session('status'),
    ])->filter();
@endphp

@if ($messages->isNotEmpty() || $errors->any())
    <div {{ $attributes->merge(['class' => 'space-y-3']) }}>
        @foreach ($messages as $type => $message)
            @php
                $isError = $type === 'error';
            @endphp
            <div role="{{ $isError ? 'alert' : 'status' }}"
                 class="flex items-start gap-3 rounded-lg p-4 text-sm ring-1 ring-inset {{ $isError ? 'bg-rose-50 text-rose-800 ring-rose-200' : 'bg-emerald-50 text-emerald-800 ring-emerald-200' }}">
                @if ($isError)
                    <svg class="mt-0.5 size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" /><path d="M12 8v5M12 16.5h.01" />
                    </svg>
                @else
                    <svg class="mt-0.5 size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" /><path d="M8 12.5l2.5 2.5L16 9.5" />
                    </svg>
                @endif
                <p class="font-medium">{{ $message }}</p>
            </div>
        @endforeach

        @if ($errors->any())
            <div role="alert" class="rounded-lg bg-rose-50 p-4 text-sm text-rose-800 ring-1 ring-inset ring-rose-200">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" /><path d="M12 8v5M12 16.5h.01" />
                    </svg>
                    <div>
                        <p class="font-medium">Please fix the following:</p>
                        <ul class="mt-1.5 list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif
