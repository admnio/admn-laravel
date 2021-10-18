<?php

namespace Auditit\AudititLaravel;

trait AuditItLoggable
{
    public static function bootAuditItLoggable(): void
    {
        static::observe(AuditItObserver::class);
    }
}
