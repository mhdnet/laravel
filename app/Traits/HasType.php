<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasType
{
    public static function bootHasType(): void
    {
        $type = strtolower(class_basename(static::class));

        static::addGlobalScope('type', function (Builder $query) use ($type) {
            $query->where('type', $type);
        });

        static::saving(function ($model) use ($type) {
            $model->type = $type;
        });
    }

    public function scopeWithoutType(Builder $query) : Builder {
        return $query->withoutGlobalScope('type');
    }
}
