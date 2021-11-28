<?php

namespace Auditit\AudititLaravel;

use Auditit\Auditit\AuditLogger;
use OwenIt\Auditing\Contracts\Audit;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\AuditDriver;

class AuditItDriver implements AuditDriver
{
    public function __construct()
    {

    }

    public function audit(Auditable $model): Audit
    {
        $builder = AuditLogger::new();

        if (auth()->check()) {
            $actor = auth()->user();

            $builder->actor('id:' . $actor->getKey());
        } else {
            $builder->actor('id:system');
        }

        $builder->source(config('audit_logger.source'));

        $builder->action('Model Operation');

        $builder->addEntity('id:' . $model->getKey());

        $builder->context($model->toAudit());
        $builder->save();

        $implementation = config('audit.implementation', \OwenIt\Auditing\Models\Audit::class);

        return new $implementation;
    }

    public function prune(Auditable $model): bool
    {
        return false;
    }
}
