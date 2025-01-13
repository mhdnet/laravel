<?php

namespace App\Models;

use App\Rules\PhoneRule;
use App\Traits\HasManyGovernorate;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;


class Business extends Model
{
    use SoftDeletes, ValidatingTrait, HasManyGovernorate;

    protected $fillable = [
        'name',
        'phone',
    ];

    protected $with = ['governorates'];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected bool $throwValidationExceptions = true;

    public function getRules(): array
    {
        return [
            'name' => 'required|string|min:3|max:50|unique',
            'phone' => ['sometimes', 'required', new PhoneRule],
            'active' => 'sometimes|boolean',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(Delegate::class)
            ->withPivot(['role', 'fare'])
            ->withTimestamps();
    }

    public static function default(): static {
        return static::first();
    }


}
