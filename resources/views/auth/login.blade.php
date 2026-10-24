<x-layouts.app title="Log in">
    <div class="mx-auto flex max-w-md flex-col px-4 py-16 sm:px-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Welcome back</h1>
            <p class="mt-1.5 text-sm text-slate-600">Log in to place orders and track your deliveries.</p>

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                @csrf

                <x-form.field label="Email address" name="email" :required="true">
                    <x-form.input name="email" type="email" autocomplete="email" required autofocus />
                </x-form.field>

                <x-form.field label="Password" name="password" :required="true">
                    <x-form.input name="password" type="password" autocomplete="current-password" required />
                </x-form.field>

                <label class="flex items-center gap-2.5 text-sm text-slate-700">
                    <input type="checkbox" name="remember" value="1"
                           class="size-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600">
                    Remember me
                </label>

                <button type="submit"
                        class="w-full rounded-lg bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                    Log in
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600">
                New here?
                <a href="{{ route('register') }}" class="font-semibold text-brand-700 hover:text-brand-800">Create an account</a>
            </p>
        </div>

        <div class="mt-6 rounded-xl border border-slate-200 bg-white p-5 text-sm">
            <p class="font-semibold text-slate-900">Demo accounts</p>
            <dl class="mt-3 space-y-2 text-slate-600">
                <div class="flex justify-between gap-4">
                    <dt>Admin</dt>
                    <dd class="font-mono text-xs text-slate-900">admin@stationeryhub.test / password</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt>Customer</dt>
                    <dd class="font-mono text-xs text-slate-900">customer@stationeryhub.test / password</dd>
                </div>
            </dl>
        </div>
    </div>
</x-layouts.app>
