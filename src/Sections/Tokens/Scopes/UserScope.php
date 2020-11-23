<?php

namespace AwemaPL\Auth\Sections\Tokens\Scopes;

use AwemaPL\Repository\Scopes\ScopeAbstract;
use AwemaPL\Repository\Scopes\ScopesAbstract;

class UserScope extends ScopeAbstract
{
    /**
     * Scope
     *
     * @param $builder
     * @param $value
     * @param $scope
     * @return mixed
     */
    public function scope($builder, $value, $scope)
    {
        if (!$value){
            return $builder;
        }
        $model = config('auth.providers.users.model');
        $userIds = $model::select('id')->where('email', 'like', '%'.$value.'%')->get('id')
            ->pluck('id')->toArray();
        return $builder->where('tokenable_type',$model)->whereIn('tokenable_id', $userIds);
    }
}
