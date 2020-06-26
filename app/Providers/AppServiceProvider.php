<?php

namespace App\Providers;

use App\User;
use Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Blade::if('admin', function (?User $user = null) {
            if ($user) {
                return $user->isAdmin();
            }

            return auth()->user()->isAdmin();
        });
    }
}
