<?php

namespace App\Policies;

use App\Constants\PermissionsName;
use App\Models\Order;
use App\Models\User;

class OrderPolicy
{

    public function create(User $user): bool
    {
        return $user->can(PermissionsName::OrderCreate);
    }


    public function update(User $user, Order $order): bool
    {
        if($order->payment_id || $order->statement_id) {
            return  false;
        }

        if (request()->filled('status')) {
            return $user->can(PermissionsName::OrderUpdateStatus);
        }

        return $user->can(PermissionsName::OrderUpdate);
    }


    public function delete(User $user, Order $order): bool
    {
        // TODO: add delete logic
        return $user->can(PermissionsName::OrderDelete);
    }


    public function restore(User $user, Order $order): bool|null
    {
        return null;
    }

    public function forceDelete(User $user, Order $order): bool|null
    {
        return null;
    }
}
