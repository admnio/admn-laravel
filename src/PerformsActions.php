<?php

namespace Admn\Admn;

use Illuminate\Support\Str;

trait PerformsActions
{
    /**
     * @param $action
     * @param $tags
     * @param $context
     * @return array|void
     */
    public function logAction($action, $tags = [], $context = [])
    {
        return AuditLogger::create($this->getAuditIdentifier(), $action, $tags, $context);
    }

    /**
     * @return AuditLogger
     */
    public final function actionBuilder(){
        return AuditLogger::make();
    }

    /**
     * @return string
     */
    protected function getAuditIdentifierKey()
    {
        return Str::snake(strtolower(config('app.name'))) . '_id';
    }

    /**
     * @return mixed
     */
    protected function getAuditIdentifierValue()
    {
        return $this->getKey();
    }

    /**
     * @return mixed
     */
    protected final function getAuditIdentifier()
    {
        return $this->getAuditIdentifierKey() . ':' . $this->getAuditIdentifierValue();
    }
}
