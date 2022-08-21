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

        foreach ($model->toArray() as $key => $value) {
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

        if (auth()->check()) {
            $user = auth()->user();
            $user->logAction('Created a ' . $model->getAuditModelName() . ' record', $model->getAuditTags(), $createdValues);
        }
    }

    public function updated(Model $model)
    {

    }

    public function updating(Model $model)
    {
        $originalValues = $model->getOriginal();
        $changedValues = $model->getChanges();

        $redactedAttributes = $model->getRedactedAuditAttributes();

        $updatedValues = [];

        foreach ($changedValues as $key => $changedValue) {
            if (in_array($key, $model->getIgnoredAuditAttributes())) {
                continue;
            }

            if (in_array($key, $redactedAttributes)) {
                $updatedValues[] = [
                    'key'      => $key,
                    'original' => $this->redact($originalValues[$key]),
                    'updated'  => $this->redact($changedValue),
                ];
            } else {
                $updatedValues[] = [
                    'key'      => $key,
                    'original' => $originalValues[$key],
                    'updated'  => $changedValue,
                ];
            }
        }

        if (count($updatedValues) && auth()->check()) {
            $user = auth()->user();
            $user->logAction('Updated a ' . $model->getAuditModelName() . ' record', $model->getAuditTags(), $updatedValues);
        }
    }

    public function deleted(Model $model)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $user->logAction('Deleted a ' . $model->getAuditModelName() . ' record', $model->getAuditTags());
        }
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
