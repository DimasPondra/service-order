<?php

namespace App\Rules;

use App\Helpers\ClientHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckUser implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = ClientHelper::checkUserByID($value);

        if (!$user) {
            $fail('The :attribute not found.');
        }
    }
}
