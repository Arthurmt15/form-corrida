<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CpfCnpj implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Letras nunca são válidas em documento.
        if (! is_string($value) || preg_match('/[a-zA-Z]/', $value)) {
            $fail('O :attribute é inválido.');

            return;
        }
        $n = preg_replace('/\D/', '', $value);
        $ok = strlen($n) === 11 ? $this->cpfOk($n) : (strlen($n) === 14 ? $this->cnpjOk($n) : false);
        if (! $ok) {
            $fail('O :attribute é inválido (dígitos verificadores).');
        }
    }

    private function cpfOk(string $n): bool
    {
        if (preg_match('/^(\d)\1{10}$/', $n)) {
            return false;
        }
        for ($t = 9; $t < 11; $t++) {
            $s = 0;
            for ($i = 0; $i < $t; $i++) {
                $s += (int) $n[$i] * (($t + 1) - $i);
            }
            if ((int) $n[$t] !== ((10 * $s) % 11) % 10) {
                return false;
            }
        }

        return true;
    }

    private function cnpjOk(string $n): bool
    {
        if (preg_match('/^(\d)\1{13}$/', $n)) {
            return false;
        }
        foreach ([[5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2], [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]] as $k => $pesos) {
            $s = 0;
            for ($i = 0; $i < 12 + $k; $i++) {
                $s += (int) $n[$i] * $pesos[$i];
            }
            $d = $s % 11 < 2 ? 0 : 11 - ($s % 11);
            if ((int) $n[12 + $k] !== $d) {
                return false;
            }
        }

        return true;
    }
}
