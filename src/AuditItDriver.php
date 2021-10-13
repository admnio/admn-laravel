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
        $actor = new User([
            'id' => 'system',
        ]);

        if (auth()->check()) {
            $actor = auth()->user();
        }

        AuditLogger::new()
            ->source('portal')
            ->actor($actor->id, null, get_class($actor))
            ->action('Model Operation')
            ->addEntity($model->getPrimaryKey(), null, get_class($model))
            ->context($model->toAudit())
            ->save();

        $implementation = Config::get('audit.implementation', \OwenIt\Auditing\Models\Audit::class);

        return new $implementation;
    }

    public function prune(Auditable $model): bool
    {
        return false;
    }
}
