<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Statement extends  Roster {

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
