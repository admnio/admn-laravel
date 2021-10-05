<?php

namespace OrisIntel\AuditLog\Traits;

trait AuditItLoggable
{
    public static function bootAuditItLoggable(): void
    {
        static::observe(AuditItObserver::class);
    }
}
