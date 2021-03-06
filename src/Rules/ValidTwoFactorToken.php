<?php

namespace AwemaPL\Auth\Rules;

use Illuminate\Contracts\Validation\Rule;
use AwemaPL\Auth\Services\Contracts\TwoFactor;

class ValidTwoFactorToken implements Rule
{
    /**
     * User model
     */
    protected $user;

    /**
     * Two factor service
     *
     * @var \AwemaPL\Auth\Services\Contracts\TwoFactor
     */
    protected $twoFactor;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user, TwoFactor $twoFactor)
    {
        $this->user = $user;

        $this->twoFactor = $twoFactor;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->twoFactor->verifyToken($this->user, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid two factor token';
    }
}
