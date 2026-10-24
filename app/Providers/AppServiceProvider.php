<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Surface N+1 queries while developing rather than in production.
        Model::preventLazyLoading(! $this->app->isProduction());

        Password::defaults(fn () => Password::min(8)->letters()->numbers());

        // The navbar badge needs the cart count on every page it renders on.
        View::composer('components.nav-bar', function ($view) {
            $view->with('cartCount', Auth::check()
                ? (int) Auth::user()->cartItems()->sum('quantity')
                : 0);
        });
    }
}
