<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use OwenIt\Auditing\Auditable as AuditableBase;
use OwenIt\Auditing\Events\AuditCustom;
use OwenIt\Auditing\Models\Audit;

trait Auditable
{
    use AuditableBase;

//    protected function creator(): Attribute
//    {
//        return Attribute::make(
//            get: fn ($value) => $this->audits->first()?->creator?->user,
//        );
//    }
    protected function getCreatedEventAttributes(): array
    {
        return [null, null];
    }

    public function syncAudit($relation, $ids, $detaching = true, $touching = true, $convert = null,): void
    {

        $this->auditEvent = 'relation';

        $this->auditCustomOld = [
            $relation => $convert ? $convert($this->{$relation}) : $this->{$relation}()->pluck('id')->all()
        ];

        $changes = $this->{$relation}()->sync($ids, $detaching);

        if (collect($changes)->flatten()->isNotEmpty()) {

            $this->auditCustomNew = [
                $relation => $convert ? $convert($this->{$relation}()->get()) : $this->{$relation}()->pluck('id')->all()
            ];

            $this->isCustomEvent = true;
            Event::dispatch(AuditCustom::class, [$this]);
            $this->isCustomEvent = false;
            if ($touching)
                $this->touch();
        }
    }

    public function updateRelationAudit($relation, $callback, $touching = true): void
    {

        $this->auditEvent = 'relation';

        $this->auditCustomOld = [
            $relation => $this->{$relation}()->pluck('id')->all(),
        ];

        if ($callback() !== false) {
            $this->auditCustomNew = [
                $relation => $this->{$relation}()->pluck('id')->all()
            ];

            $this->isCustomEvent = true;
            Event::dispatch(AuditCustom::class, [$this]);
            $this->isCustomEvent = false;
            if ($touching) {
                $this->touch();
            }
        }
    }

    public function getModifiedAttributes(string|null $date): Collection|null
    {
        if ($this->relationLoaded('audits')) {
            $rows = $this->audits
                ->whereIn('event', ['created', 'updated', 'relation'])
                ->where('updated_at', '>=', $date);

            $attributes = collect(['id']);


            if ($rows->firstWhere('event', 'created')) {
                $attributes = $attributes->concat(array_keys($this->toArray()));
                $rows->shift();
            }

            if ($rows->isNotEmpty()) {

                return $rows->reduce(function (
                    Collection $attributes, Audit $audit) {

                    return $attributes->merge(array_keys($audit->new_values));

                }, $attributes)->unique();
            }


        }

        return null;
    }
}
