<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
class EmailOrPhone implements ValidationRule
{


    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!(Validator::make([$attribute => $value], [$attribute => 'email'])->passes() ||
            Validator::make([$attribute => $value], [$attribute => new PhoneRule])->passes())) {
            $fail('validation.regex')->translate();
        }
    }
}
