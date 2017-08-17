<?php

namespace App\Providers;

use App\Exceptions\Identifier;
use GrahamCampbell\Exceptions\ExceptionIdentifier;
use Illuminate\Support\ServiceProvider;

class ExceptionOverridesProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ExceptionIdentifier::class, function() {
            return new Identifier();
        });
    }
}
