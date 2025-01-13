<?php

namespace App\Traits;


use App\Events\ServerUpdated;
use Cache;
use Carbon\Carbon;
use Illuminate\Support\Arr;


trait HasSynchronize
{
    public static function bootHasSynchronize(): void
    {
        static::saved(function (self $model) {
            $model->sendEvent($model);
        });

        static::deleted(function (self $model) {
            $model->sendEvent($model);
        });
    }

    protected function sendEvent(self $model): void
    {
        broadcast(new ServerUpdated())->toOthers();

        $name = class_basename(static::class);
        Cache::forever('last_update_' . $name, now());


        if (!!($id = $model->getAttribute('account_id')) && $name != 'Roster') {
            $cached = Cache::get('last_update_client_models', []);

            $cached[$id][class_basename(static::class)] = now();

            Cache::forever('last_update_client_models', $cached);
        }
    }

    public static function getCached(string|null $date): bool
    {
        if (!$date)
            return false;

        $lastUpdates = null;

        $name = class_basename(static::class);
        if (request()->isAdminRoute()) {
            $lastUpdates = Cache::get('last_update_' . $name);

        } elseif (request()->isClientRoute()) {
            $account = request()->account()->id;

            /** @var array $cached */
            $cached = Cache::get('last_update_client_models', []);

            if (is_null($lastUpdates = Arr::get($cached, $account.'.'. $name))) {
                $lastUpdates = Cache::get('last_update_' . $name);
            }
        }

        if (!$lastUpdates)
            return false;
        return Carbon::createFromTimeString($date)->gt($lastUpdates);

    }
}
