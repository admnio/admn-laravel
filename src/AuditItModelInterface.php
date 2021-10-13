<?php

namespace Auditit\AudititLaravel;

/**
 *
 */
interface AuditItModelInterface extends \OwenIt\Auditing\Contracts\Auditable
{
    /**
     * @return int|string
     */
    public function getAuditIdentifier();

    /**
     * @return string
     */
    public function getAuditDisplay();

    /**
     * @return string
     */
    public function getAuditType();
}
