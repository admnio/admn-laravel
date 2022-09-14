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
        $this->publishes([
            __DIR__.'/config/admn.php' => config_path('admn.php'),
        ]);

        AuditLogger::setCredentials(config('admn.token'), config('admn.secret'));
    }
}
