<?php

namespace Auditit\AudititLaravel;


trait LogChangesToAuditIt
{
    protected $redactedAuditAttributes = [];

    protected $auditModelName = '';

    protected $ignoreAuditAttributes = [
        'updated_at'
    ];

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
        $class =  new \ReflectionClass(get_class($this));
        return empty($this->auditModelName) ? $class->getShortName() : strtoupper($this->auditModelName);
    }

    public function getRedactedAuditAttributes()
    {
        return $this->redactedAuditAttributes;
    }

    public function getIgnoredAuditAttributes(){
        return $this->ignoreAuditAttributes;
    }
}
