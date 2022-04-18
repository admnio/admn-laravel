<?php

namespace Admn\Admn;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AdmnObserver
{

    public function creating(Model $model)
    {

    }

    public function created(Model $model)
    {
        $redactedAttributes = $model->getRedactedAuditAttributes();
        $createdValues = [];

        foreach ($model->getAttributes() as $key => $value) {
            if (in_array($key, $model->getIgnoredAuditAttributes())) {
                continue;
            }

            if (in_array($key, $redactedAttributes)) {
                $createdValues[] = [
                    'key'      => $key,
                    'original' => $this->redact($value),
                ];
            } else {
                $createdValues[] = [
                    'key'      => $key,
                    'original' => $value,
                ];
            }
        }

        AuditLogger::create(
            auth()->check() ? 'id:' . auth()->user()->getKey() : 'id:system',
            'Created a ' . $model->getAuditModelName() . ' record',
            $model->getAuditTags(),
            $createdValues
        );
    }

    public function updated(Model $model)
    {
        $originalValues = $model->getOriginal();
        $redactedAttributes = $model->getRedactedAuditAttributes();

        $updatedValues = [];

        foreach ($originalValues as $key => $originalValue) {
            if (in_array($key, $model->getIgnoredAuditAttributes())) {
                continue;
            }

            if ($originalValue != $model->$key) {

                if (in_array($key, $redactedAttributes)) {
                    $updatedValues[] = [
                        'key'      => $key,
                        'original' => $this->redact($originalValue),
                        'updated'  => $this->redact($model->$key),
                    ];
                } else {
                    $updatedValues[] = [
                        'key'      => $key,
                        'original' => $originalValue,
                        'updated'  => $model->$key,
                    ];
                }
            }
        }

        if (count($updatedValues)) {
            AuditLogger::create(
                auth()->check() ? 'id:' . auth()->user()->getKey() : 'id:system',
                'Updated a ' . $model->getAuditModelName() . ' record',
                $model->getAuditTags(),
                $updatedValues
            );
        }
    }

    public function deleted(Model $model)
    {
        $redactedAttributes = $model->getRedactedAuditAttributes();
        $deletedValues = [];

        foreach ($model->getAttributes() as $key => $value) {
            if (in_array($key, $model->getIgnoredAuditAttributes())) {
                continue;
            }

            if (in_array($key, $redactedAttributes)) {
                $deletedValues[] = [
                    'key'      => $key,
                    'original' => $this->redact($value),
                ];
            } else {
                $deletedValues[] = [
                    'key'      => $key,
                    'original' => $value,
                ];
            }
        }

        AuditLogger::create(
            auth()->check() ? 'id:' . auth()->user()->getKey() : 'id:system',
            'Deleted a ' . $model->getAuditModelName() . ' record',
            $model->getAuditTags(),
            $deletedValues
        );
    }


    public function restored(Model $model)
    {

    }

    public function forceDeleted(Model $model)
    {

    }

    private function redact($string)
    {
        return Str::mask($string, '*', 0, strlen($string));
    }
}
