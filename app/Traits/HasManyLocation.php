<?php

namespace App\Traits;

use App\Models\Location;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasManyLocation
{
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class);
    }
}
