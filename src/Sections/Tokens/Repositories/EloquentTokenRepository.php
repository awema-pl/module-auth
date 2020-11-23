<?php

namespace AwemaPL\Auth\Sections\Tokens\Repositories;

use AwemaPL\Auth\Sections\Tokens\Models\PlainToken;
use AwemaPL\Auth\Sections\Tokens\Models\Token;
use AwemaPL\Auth\Sections\Tokens\Repositories\Contracts\TokenRepository;
use AwemaPL\Auth\Sections\Tokens\Scopes\EloquentTokenScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class EloquentTokenRepository extends BaseRepository implements TokenRepository
{
    protected $searchable = [
        'name' => 'like',
    ];

    public function entity()
    {
        return PersonalAccessToken::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentTokenScopes($request))->scope($this->entity);

        return $this;
    }

    /**
     * Create token
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data){
        $user = config('auth.providers.users.model')::findOrFail($data['user_id']);
        $token = $user->createToken($data['name']);
        $plainToken = $token->plainTextToken;
        PlainToken::updateOrCreate([
            'token_id' =>$token->accessToken->id,
        ], [
            'plain_token' => encrypt($plainToken),
        ]);
        return $plainToken;
    }

    /**
     * Update token
     *
     * @param array $data
     * @return int
     */
    public function change($id)
    {
        $personalAccesstoken = PersonalAccessToken::findOrFail($id);
        $nameToken = $personalAccesstoken->name;
        $user = config('auth.providers.users.model')::findOrFail($personalAccesstoken->tokenable_id);
        $personalAccesstoken->delete();
        $token = $user->createToken($nameToken);
        $plainToken = $token->plainTextToken;
        PlainToken::create([
            'token_id' =>$token->accessToken->id,
            'plain_token' => encrypt($plainToken),
        ]);
        return $plainToken;
    }

    /**
     * Delete token
     *
     * @param array $data
     *
     * @return int
     */
    public function delete($id){
        PersonalAccessToken::findOrFail($id)->delete();
    }

}
