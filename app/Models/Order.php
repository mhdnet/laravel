<?php

namespace App\Models;

use App\Casts\AsOrderStatus;
use App\Observers\OrderObserver;
use App\Traits\Auditable;
use App\Traits\HasGovernorate;
use App\Traits\HasLocation;
use Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Order extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, SoftDeletes, HasLocation, HasGovernorate, Auditable;

    protected $fillable = [
        'value',
        'no',
        'time',
        'address',
        'name',
        'governorate_id',
        'location_id',
    ];

    protected $with = ['phones', 'audits'];

    protected $casts = [
        'status' => AsOrderStatus::class,
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::observe(OrderObserver::class);
    }

    public function syncPhones(array $phones): void
    {
        $phones = collect($phones)->map(function ($number) {
            return Phone::firstOrCreate(['number' => $number]);
        });

        $this->syncAudit('phones', $phones->pluck('id')->all());

    }

    public function phones(): BelongsToMany
    {
        return $this->belongsToMany(Phone::class)->withTimestamps();
    }


    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function statement(): BelongsTo
    {
        return $this->belongsTo(Statement::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Statement::class);
    }

    public function roster(): BelongsTo
    {
        return $this->belongsTo(Roster::class);
    }

//    public function clientCachedKey():string
//    {
//        return $this->account_id. '_last_update_' . class_basename(static::class);
//    }



}
