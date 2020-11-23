<?php

namespace AwemaPL\Auth\Sections\Users\Repositories\Contracts;

use AwemaPL\Auth\Sections\Options\Http\Requests\UpdateOption;
use Illuminate\Http\Request;

interface UserRepository
{
    /**
     * Get existing user by social service data
     *
     * @param array $serviceUser
     * @param string $service
     * @return \App\User
     */
    public function getUserBySocial($serviceUser, $service);

    /**
     * Create new user
     *
     * @param array $data
     * @return \App\User
     */
    public function store(array $data);

    /**
     * Create user
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope user
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
    
    /**
     * Update user
     *
     * @param array $data
     * @param int $id
     *
     * @return int
     */
    public function update(array $data, $id);
    
    /**
     * Delete user
     *
     * @param int $id
     */
    public function delete($id);

}
