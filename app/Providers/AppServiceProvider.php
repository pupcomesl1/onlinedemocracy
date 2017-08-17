<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', 'App\Http\ViewComposers\AuthBaseComposer');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
