<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class Celular implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Somente números e máscara; letras nunca são válidas.
        if (! is_string($value) || preg_match('/[a-zA-Z]/', $value) || ! preg_match('/^[\d()\- ]+$/', $value)) {
            $fail('O :attribute é inválido (somente números, com DDD).');

            return;
        }
        if (! in_array(strlen(preg_replace('/\D/', '', $value)), [10, 11], true)) {
            $fail('O :attribute é inválido (somente números, com DDD).');
        }
    }
}
