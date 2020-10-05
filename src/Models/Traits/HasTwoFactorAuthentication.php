<?php

namespace AwemaPL\Auth\Models\Traits;

use AwemaPL\Auth\Models\TwoFactor;

trait HasTwoFactorAuthentication
{
    /**
     * Define an \AwemaPL\Auth\Models\TwoFactor relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function twoFactor()
    {
        return $this->hasOne(TwoFactor::class);
    }

    /**
     * Check if two-factor auth is enabled
     *
     * @return boolean
     */
    public function isTwoFactorEnabled()
    {
        return (bool) optional($this->twoFactor)->isVerified();
    }

    /**
     * Check if two-factor verification pending
     *
     * @return boolean
     */
    public function isTwoFactorPending()
    {
        if (! $this->twoFactor) {
            return false;
        }
        return ! $this->twoFactor->isVerified();
    }
}
