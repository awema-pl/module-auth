<?php

namespace AwemaPL\Auth\Models\Traits;

use AwemaPL\Auth\Models\UserSocial;

trait HasSocialAuthentication
{
    /**
     * Define an \AwemaPL\Auth\Models\UserSocial relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function social()
    {
        return $this->hasMany(UserSocial::class);
    }

    /**
     * Check if has any \AwemaPL\Auth\Models\UserSocial relationships.
     *
     * @param string $service
     * @return boolean
     */
    public function hasSocial($service)
    {
        return $this->social->where('service', $service)->count() > 0;
    }
}
