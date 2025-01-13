<?php

namespace App\Http\Middleware;

use App\Rules\PhoneRule;
use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class CleanPhones  extends TransformsRequest
{
   protected function transform($key, $value)
   {
       if (!is_string($value)) return  $value;
       return PhoneRule::cleanPhone($value);
   }
}
