<?php

namespace AwemaPL\Auth\Sections\Tokens\Repositories\Contracts;

use AwemaPL\Auth\Sections\Options\Http\Requests\UpdateOption;
use Illuminate\Http\Request;

interface TokenRepository
{
    /**
     * Create token
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope token
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
    
    /**
     * Delete token
     *
     * @param array $data
     *
     * @return int
     */
    public function delete($id);

    /**
     * Change token
     *
     * @param int $id
     * @return int
     */
    public function change($id);

}
