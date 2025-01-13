<?php

namespace App\Observers;


use App\Models\Admin;
use App\Models\Order;
use App\Models\Plan;
use Auth;

class OrderObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public bool $afterCommit = true;

    public function creating(Order $order): void
    {
        $this->updateFareAndTax($order);

        if (request()->isAdminRoute()) {
            $order->status->toReceived();
        }
    }

    public function updating(Order $order): void
    {
        if ($order->isDirty('fare'))
            $this->updateFareAndTax($order);

        if ($order->isDirty('governorate_id')) {
            $standard = $this->getStandardTaxi($order);
            $order->fare = $standard + $order->tax;
        }
    }

    public function saving(Order $order): void
    {
        if ($order->isDirty('status') && $order->status->isDelayed) {
            $order->delay_count++;
        }
    }

    public function saved(Order $order): void
    {
        if ($order->isDirty('no')) {

            $order->account->syncLedgers($order->no, $order->getOriginal('no'));

        }
    }

    public function created(Order $order)
    {
        // TODO: add notify to user
    }

    protected function updateFareAndTax(Order $order): void
    {
        $standard = $this->getStandardTaxi($order);

        $order->fare = $order->fare ?? $standard;

        $order->tax = $order->fare - $standard;

    }

    protected function getStandardTaxi(Order $order): int
    {
        /** @var Plan $plan */
        $plan = $order->account->plan;

        return $plan->calculate($order->governorate);
    }

}
