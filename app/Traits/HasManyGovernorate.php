<?php

namespace App\Traits;

use App\Models\Governorate;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasManyGovernorate
{
    public function governorates(): BelongsToMany
    {
        return $this->belongsToMany(Governorate::class);
    }
}
