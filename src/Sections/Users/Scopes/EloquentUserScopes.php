<?php

namespace AwemaPL\Auth\Sections\Users\Scopes;

use AwemaPL\Repository\Scopes\ScopesAbstract;

class EloquentUserScopes extends ScopesAbstract
{
    protected $scopes = [
        'q'=>SearchUser::class,
    ];
}
