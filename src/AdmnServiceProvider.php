<?php

namespace Admn\Admn;

use Illuminate\Support\ServiceProvider;

class AdmnServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        AuditLogger::setCredentials(config('services.admn.token'), config('services.admn.secret'));
    }
}
