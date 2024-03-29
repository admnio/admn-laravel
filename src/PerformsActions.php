<?php

namespace Admn\Admn;

use Illuminate\Support\Str;

trait PerformsActions
{
    /**
     * @param string $action
     * @param array $tags
     * @param array $context
     * @return array
     * @throws \Exception
     */
    public function logAction(string $action, array $tags = [], array $context = [])
    {
        return AuditLogger::make(
            (new Actor)
                ->setIdentifier(
                    $this->getAuditIdentifierKey(), $this->getAuditIdentifierValue()
                )
                ->setDisplay($this->getAuditDisplayValue())
        )->setAction($action)
            ->setTags($tags)
            ->setContext($context)
            ->save();
    }

    /**
     * @return string
     */
    protected function getAuditIdentifierKey()
    {
        return Str::snake(strtolower(config('app.name'))) . '_id';
    }

    /**
     * @return string
     */
    protected function getAuditDisplayValue()
    {
        if($this->getAuditIdentifier()){
            return $this->getAuditIdentifier();
        }

        if (method_exists($this, '__toString')) {
            return $this->__toString();
        }

        return $this->getKey();
    }

    /**
     * @return string|int
     */
    protected function getAuditIdentifierValue()
    {
        return $this->getKey();
    }

    /**
     * @return string
     */
    protected final function getAuditIdentifier()
    {
        return $this->getAuditIdentifierKey() . ':' . $this->getAuditIdentifierValue();
    }
}
