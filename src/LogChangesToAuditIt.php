<?php

namespace Admn\Admn;


trait LogChangesToAuditIt
{
    /**
     * Auditable boot logic.
     *
     * @return void
     */
    public static function bootLogChangesToAuditIt()
    {
        static::observe(new AdmnObserver());
    }

    public function getAuditModelName()
    {
        $class = new \ReflectionClass(get_class($this));

        return empty($this->auditModelName) ? $class->getShortName() : strtoupper($this->auditModelName);
    }

    public function getRedactedAuditAttributes()
    {
        return (array)$this->redactedAuditAttributes;
    }

    public function getIgnoredAuditAttributes()
    {
        return (array)$this->ignoreAuditAttributes;
    }

    public function getAuditTags()
    {
        return [
            $this->getAuditModelName() . ':' . $this->getKey(),
            'action-type:model audit'
        ];
    }
}
