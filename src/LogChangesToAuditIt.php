<?php

namespace Auditit\AudititLaravel;


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
        static::observe(new AuditItObserver());
    }

    public function getAuditModelName()
    {
        return empty($this->auditModelName) ? get_class($this) : strtoupper($this->auditModelName);
    }

    public function getRedactedAuditAttributes()
    {
        return $this->redactedAuditAttributes;
    }
}
