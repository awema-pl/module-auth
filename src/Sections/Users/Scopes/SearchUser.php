<?php

namespace AwemaPL\Auth\Sections\Users\Scopes;
use AwemaPL\Repository\Scopes\ScopeAbstract;

class SearchUser extends ScopeAbstract
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

        return $builder->where('email', 'like', '%'.$value.'%');
    }
}
