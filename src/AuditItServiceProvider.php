<?php

namespace Auditit\AudititLaravel;

use Illuminate\Support\ServiceProvider;

class AuditItServiceProvider extends ServiceProvider
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
            __DIR__.'/config/audit_logger.php' => config_path('audit_logger.php'),
        ]);

        $this->publishes([
            __DIR__.'/config/audit.php' => config_path('audit.php'),
        ]);
    }
}
