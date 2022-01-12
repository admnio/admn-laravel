<?php

namespace Admn\Admn;

trait PerformsActions
{
    /**
     * @param $action
     * @param array $tags
     * @param array $context
     */
    public function logAction($action, $tags = [], $context = [])
    {
        return \Auditit\Auditit\AuditLogger::create('id:' . $this->{$this->getKeyName()}, $action, $tags, $context);
    }
}
