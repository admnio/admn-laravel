<?php

namespace Auditit\AudititLaravel;

use OwenIt\Auditing\AuditableObserver;


trait LogChangesToAuditIt
{
    protected $redactedAuditAttributes = [];

    protected $auditModelName = '';

    /**
     * Auditable boot logic.
     *
     * @return void
     */
    public static function bootLogChangesToAuditIt()
    {
        static::observe(new AuditableObserver());
    }

    public function getAuditModelName()
    {
        return empty($this->auditModelName) ? get_class($this) : $this->auditModelName;
    }

    public function getRedactedAuditAttributes()
    {
        return strtoupper($this->auditModelName);
    }
}
