<?php

namespace AwemaPL\Auth\Widgets\Tokens\Http\Requests\Rules;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class PasswordValidation implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Hash::check($value, request()->user()->password);
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return _p('auth::requests.widget.token.messages.incorrect_password', 'Incorrect password.');
    }
}
