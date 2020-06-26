<?php

namespace App\Providers;

use App\Services\KronoxService;
use Illuminate\Support\ServiceProvider;

class KronoxServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('kronox', function ($app) {
            return new KronoxService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
