<?php

namespace Auditit\AudititLaravel;

use Auditit\Auditit\AuditLogger;
use Illuminate\Database\Eloquent\Model;

class AuditItObserver
{

    public function creating(Model $model)
    {

    }

    public function created(Model $model)
    {
        $redactedAttributes = $model->getRedactedAuditAttributes();
        $createdValues = [];

        foreach($model->getAttributes() as $key => $value){
            if (in_array($key, $redactedAttributes)) {
                $updatedValues[] = [
                    'key'      => $key,
                    'original' => $this->redact($value),
                ];
            } else {
                $updatedValues[] = [
                    'key'      => $key,
                    'original' => $value,
                ];
            }
        }

        AuditLogger::create(
            'id:'.auth()->user() ?: 'id:system',
            'Created a record'.$model->getAuditModelName(),
            ['model_type:'.$model->getAuditModelName(), 'model_id:'.$model->getKey()],
            $createdValues
        );
    }

    public function updated(Model $model)
    {
        $originalValues = $model->getOriginal();
        $redactedAttributes = $model->getRedactedAuditAttributes();

        $updatedValues = [];

        foreach ($originalValues as $key => $originalValue) {
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

        AuditLogger::create(
            'id:'.auth()->user() ?: 'id:system',
            'Updated created a record'.$model->getAuditModelName(),
            ['model_type:'.$model->getAuditModelName(), 'model_id:'.$model->getKey()],
            $updatedValues
        );
    }

    public function deleted(Model $model)
    {
        $redactedAttributes = $model->getRedactedAuditAttributes();
        $deletedValues = [];

        foreach($model->getAttributes() as $key => $value){
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
            'id:'.auth()->user() ?: 'id:system',
            'Deleted a record'.$model->getAuditModelName(),
            ['model_type:'.$model->getAuditModelName(), 'model_id:'.$model->getKey()],
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
        $length = strlen(($string));

        return str_repeat('*', $length);
    }
}
