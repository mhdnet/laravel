<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasType;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class Roster extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, HasType, Auditable;

    protected $table = 'statements';

    protected $fillable = [];

    protected $with = ['orders', 'audits.creator.user'];

    protected $casts = [
        'exported_at' => 'datetime',
        'achieved_at' => 'datetime',
    ];

    public array $auditEvents = [
        'created',
        'deleted',
    ];

    protected function getDeletedEventAttributes(): array
    {
        $old = array_filter(Arr::except($this->attributes, ['type']), fn($value) => !is_null($value));

        $old['orders'] = $this->orders()->pluck('id');

        return [$old, null];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'account_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'receiver_id');
    }

    public function delegate(): BelongsTo
    {
        return $this->belongsTo(Delegate::class, 'delegate_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function syncOrders($orders = []): void
    {
        if (is_array($orders))
            $orders = collect($orders);

        $orders = $orders->map(fn($item) => $item instanceof Order ? $item->id : $item);


        $ids = $this->orders()->pluck('id');

        $add = $orders->diff($ids);

        $remove = $ids->diff($orders);

        $needTouched = false;

        if ($remove->isNotEmpty()) {

            $this->orders()->whereKey($remove->all())
                ->each(function (Order $order) {
                    $order->forceFill([$this->getForeignKey() => null])->save();
                });


            $needTouched = true;
        }

        if ($add->isNotEmpty()) {

            Order::whereKey($add->all())->each(function (Order $order) {

                $order->forceFill([$this->getForeignKey() => $this->getKey()])->save();
            });

//            Order::withoutTimestamps(function () use ($add) {
//            });


            $needTouched = true;

        }

        if ($needTouched) {

            $this->value = Order::whereKey($orders)
                ->select(\DB::raw('SUM(value) as amount'))->value('amount') ?: 0;
            $this->fare = Order::whereKey($orders)
                ->select(\DB::raw('SUM(fare) as amount'))->value('amount') ?: 0;

            $this->save();
        }

    }

    public function addOrders($orders = []): static
    {
        $this->save();

        $this->syncOrders($orders);

        return $this;
    }

    public function receive($receiver = null): void
    {
        $this->achieved_at = now();

        $this->receiver()->associate($receiver ?: Auth::user());

        $this->save();
    }

    /**
     * @throws \Throwable
     */
    public function export($delegate = null): void
    {
        DB::transaction(function () use ($delegate) {
            $this->exported_at = now();

            if ($delegate)
                $this->delegate()->associate($delegate);
            elseif (($user = Auth::user()) instanceof Delegate)
                $this->delegate()->associate($user);


            $this->orders()->each(function (Order $order) {
                if ($this->type == 'roster')
                    $order->status->toShipping();
                else
                    $order->status->toCompleted($this->type);
                $order->save();
            });

            $this->save();
        });
    }

//    public function clientCachedKey():null|string
//    {
//        return $this->account_id. '_last_update_' . class_basename(static::class);
//    }
}


