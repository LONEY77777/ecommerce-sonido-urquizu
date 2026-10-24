<x-layouts.app title="Create an account">
    <div class="mx-auto flex max-w-md flex-col px-4 py-16 sm:px-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Create your account</h1>
            <p class="mt-1.5 text-sm text-slate-600">It takes a moment, and your cart is saved between visits.</p>

            <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
                @csrf

                <x-form.field label="Full name" name="name" :required="true">
                    <x-form.input name="name" autocomplete="name" required autofocus />
                </x-form.field>

                <x-form.field label="Email address" name="email" :required="true">
                    <x-form.input name="email" type="email" autocomplete="email" required />
                </x-form.field>

                <x-form.field label="Password" name="password" :required="true"
                              hint="At least 8 characters, including letters and numbers.">
                    <x-form.input name="password" type="password" autocomplete="new-password" required />
                </x-form.field>

                <x-form.field label="Confirm password" name="password_confirmation" :required="true">
                    <x-form.input name="password_confirmation" type="password" autocomplete="new-password" required />
                </x-form.field>

                <button type="submit"
                        class="w-full rounded-lg bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                    Create account
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-semibold text-brand-700 hover:text-brand-800">Log in</a>
            </p>
        </div>
    </div>
</x-layouts.app>
