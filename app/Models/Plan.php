<?php

namespace App\Models;

use App\Traits\HasGovernorate;
use App\Traits\HasManyGovernorate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;

class Plan extends Model
{
    use HasFactory, ValidatingTrait, SoftDeletes, HasManyGovernorate, HasGovernorate;

    protected $fillable = [
        'name',
        'fare',
        'description',
        'is_default',
        'governorate_id',
    ];

    protected $with = ['governorates'];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    protected bool $throwValidationExceptions = true;

    protected array $rules = [
        'name' => 'required|string|min:3|unique',
        'fare' => 'required|numeric|min:1000',
        'description' => 'nullable|string|max:250',
        'is_default' => 'sometimes|required|boolean',
        'governorate_id' => 'nullable|exists:governorates,id',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::observe(\App\Observers\PlanObserver::class);
    }

    public function governorates(): BelongsToMany
    {
        return $this->belongsToMany(Governorate::class)->withPivot('fare');
    }

    public function calculate(int|Governorate $governorate): int
    {
        if ($exists = $this->governorates()->find($governorate)) {
            return $exists->pivot->fare;
        }

        return $this->fare;
    }

    /**
     * get th default (or first) plan
     *
     * @return Plan|null
     */
    public static function default(): static|null
    {
        return static::firstWhere('is_default', true);
    }


    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

}
