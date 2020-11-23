<?php

namespace AwemaPL\Auth\Sections\Users\Repositories;

use AwemaPL\Auth\Sections\Users\Models\User;
use AwemaPL\Auth\Sections\Users\Repositories\Contracts\UserRepository;
use AwemaPL\Auth\Sections\Users\Scopes\EloquentUserScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class EloquentUserRepository extends BaseRepository implements UserRepository
{
    protected $searchable = [

    ];

    public function entity()
    {
        return getModelForGuard(config('auth.defaults.guard'));
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentUserScopes($request))->scope($this->entity);

        return $this;
    }

    /**
     * Create new role
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->store($data);
    }

    /**
     * Update user
     *
     * @param array $data
     * @param int $id
     * @param string $attribute
     *
     * @return int
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $model = $this->entity->where($attribute, $id)->firstOrFail();
        $model->mergeFillable(['email_verified_at']);
        $results = $model->update($data);
        $this->reset();
        return $results;
    }

    /**
     * Delete user
     *
     * @param int $id
     */
    public function delete($id){
        $this->destroy($id);
    }

    /**
     * Get existing user by social service data
     *
     * @param array $serviceUser
     * @param string $service
     * @return
     */
    public function getUserBySocial($serviceUser, $service)
    {
        return getModelForGuard(config('auth.defaults.guard'))::where('email', $serviceUser->getEmail())
            ->orWhereHas('social',
                function ($query) use ($serviceUser, $service) {
                    $query->where('social_id', $serviceUser->getId())->where('service', $service);
                }
            )->first();
    }

    /**
     * Create new user
     *
     * @param array $data
     * @return
     */
    public function store(array $data)
    {
        /** @var Model $model */
        $model = new $this->entity($data);
        $model->mergeFillable(['email_verified_at']);
        $model->email_verified_at = $data['email_verified_at'] ?? null;
        $model->save();
        $this->reset();
        return $model;
    }

}
