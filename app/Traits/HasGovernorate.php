<?php

namespace App\Traits;

use App\Models\Governorate;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasGovernorate
{
    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }
}
