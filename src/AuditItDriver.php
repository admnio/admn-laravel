<?php

namespace Auditit\AudititLaravel;

use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Config;

use League\Csv\Writer;
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

            $builder->actor($actor->getAuditIdentifier(), $actor->getAuditDisplay(), $actor->getAuditType());
        } else {
            $builder->actor(0, 'system', 'system');
        }

        $builder->source(config('audit_logger.source'));

        $builder->action('Model Operation');

        $builder->addEntity($model->getAuditIdentifier(), $model->getAuditDisplay(), $model->getAuditType());

        $builder->context($model->toAudit());
        $builder->save();

        $implementation = Config::get('audit.implementation', \OwenIt\Auditing\Models\Audit::class);

        return new $implementation;
    }

    public function prune(Auditable $model): bool
    {
        return false;
    }
}
