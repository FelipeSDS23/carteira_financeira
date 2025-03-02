<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;

class CpfValidation implements Rule
{
    public function passes($attribute, $value)
    {
        return User::isValidCpf($value);
    }

    public function message()
    {
        return 'O CPF informado não é válido.';
    }
}
