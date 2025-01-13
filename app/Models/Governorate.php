<?php

namespace App\Models;


use App\Traits\HasManyLocation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;

class Governorate extends Model
{
    use ValidatingTrait, HasManyLocation, SoftDeletes;

    protected $fillable = ['name'];

    protected bool $throwValidationExceptions = true;

    protected array $rules = [
        'name' => 'required|unique',
    ];

    public static function default(): static|null
    {
        return static::firstWhere('id', config('settings.default_location'));
    }



}
