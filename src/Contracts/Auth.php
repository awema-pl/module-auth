<?php

namespace AwemaPL\Auth\Contracts;

interface Auth
{
    /**
     * Register routes.
     *
     * @return void
     */
    public function routes();

    /**
     * Register user has API tokens
     *
     * @throws \ReflectionException
     */
    public function registerUserHasApiTokens();

    /**
     * Menu merge in navigation
     */
    public function menuMerge();

    /**
     * Add permissions for module permission
     */
    public function mergePermissions();

    /**
     * Add widgets
     */
    public function addWidgets();
}
