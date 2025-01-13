<?php

namespace App\Observers;

use App\Models\Governorate;
use App\Models\Plan;

class PlanObserver
{

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public bool $afterCommit = true;

    /**
     * Handle the Plan "saving" event.
     */
    public function saving(Plan $plan): void
    {
        if(!$plan->governorate_id) {
            $plan->governorate_id = Governorate::default()?->id;
        }

        if(!$plan->is_default && Plan::count() == 0) {
            $plan->is_default = true;
        }
    }

    /**
     * Handle the Plan "saved" event.
     */
    public function saved(Plan $plan): void
    {
        if($plan->isDirty('is_default') && $plan->is_default && Plan::count() > 1) {
            Plan::withTrashed()
                ->whereKeyNot($plan->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
    }
}
