<?php

namespace Auditit\AudititLaravel;

use Auditit\Auditit\AuditLogger;
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
            __DIR__ . '/config/audit_logger.php' => config_path('audit_logger.php'),
        ]);

        AuditLogger::setCredentials(config('audit_logger.token'), config('audit_logger.secret'));
    }
}
