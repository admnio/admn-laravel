<?php

namespace Auditit\AudititLaravel;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use OwenIt\Auditing\AuditableObserver;
use OwenIt\Auditing\Contracts\AttributeEncoder;
use OwenIt\Auditing\Contracts\AttributeRedactor;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\IpAddressResolver;
use OwenIt\Auditing\Contracts\UrlResolver;
use OwenIt\Auditing\Contracts\UserAgentResolver;
use OwenIt\Auditing\Contracts\UserResolver;
use OwenIt\Auditing\Exceptions\AuditableTransitionException;
use OwenIt\Auditing\Exceptions\AuditingException;
use OwenIt\Auditing\Models\Audit;

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
        return $this->auditModelName;
    }
}
