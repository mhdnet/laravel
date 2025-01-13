<?php

namespace OwenIt\Auditing\Drivers;

use Illuminate\Support\Facades\Config;
use OwenIt\Auditing\Contracts\Audit;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\AuditDriver;

class Database implements AuditDriver
{
    /**
     * {@inheritdoc}
     */
    public function audit(Auditable $model): ?Audit
    {

        $implementation = Config::get('audit.implementation', \OwenIt\Auditing\Models\Audit::class);
        /** @var \OwenIt\Auditing\Models\Audit $audit */
        $audit = new $implementation;

        $creator = \OwenIt\Auditing\Models\AuditCreator::firstOrCreate($model->toAuditCreator());

        $audit->fill($model->toAudit());

        $audit->creator()->associate($creator);

        $audit->save();

        return $audit;
    }

    /**
     * {@inheritdoc}
     */
    public function prune(Auditable $model): bool
    {
        if (($threshold = $model->getAuditThreshold()) > 0) {
            $forRemoval = $model->audits()
                ->latest()
                ->get()
                ->slice($threshold)
                ->pluck('id');

            if (!$forRemoval->isEmpty()) {
                return $model->audits()
                    ->whereIn('id', $forRemoval)
                    ->delete() > 0;
            }
        }

        return false;
    }
}
