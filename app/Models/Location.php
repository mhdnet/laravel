<?php

namespace App\Models;

use App\Traits\HasManyGovernorate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Watson\Validating\ValidatingTrait;


class Location extends Model
{
    use  ValidatingTrait, HasManyGovernorate, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    protected $with = ['governorates'];

    protected $casts = [
        'trusted' => 'boolean',
        'aliases' => 'array',
    ];

    protected bool $throwValidationExceptions = true;

    protected array $rules = [
        'name' => 'required|string|min:3',
        'aliases' => 'nullable|array',
        'trusted' => 'sometimes|boolean',
    ];

    public function governorates(): BelongsToMany
    {
        return $this->belongsToMany(Governorate::class)->withTimestamps();
    }
}
