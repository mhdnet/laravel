<?php

namespace App\Models;


class Admin extends User
{
    protected string $guard_name = "admin";
    public function asDelegate(): Delegate|null
    {
        return Delegate::withoutType()->find($this->id);
    }
}
