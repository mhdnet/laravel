<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class PhoneRule implements ValidationRule
{
    private static string $pattern = "/^(\+964)?0?(7[5-9]{1}\d{8})$/i";
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if(!preg_match(static::$pattern, $value)){
            $fail('validation.regex')->translate();
        }

    }

    public static function cleanPhone(string $value): string|null
    {
        return preg_replace(static::$pattern, '$2', $value);
    }
}
