<?php

namespace AwemaPL\Auth\Sections\Tokens\Scopes;

use AwemaPL\Repository\Scopes\ScopesAbstract;

class EloquentTokenScopes extends ScopesAbstract
{
    protected $scopes = [
        'user' => UserScope::class,
    ];
}
