<?php

namespace AwemaPL\Auth\Sections\Tokens\Http\Controllers;

use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Auth\Sections\Tokens\Http\Requests\ShowApiTokenWidget;
use AwemaPL\Auth\Sections\Tokens\Http\Requests\StoreToken;
use AwemaPL\Auth\Sections\Tokens\Http\Requests\UpdateToken;
use AwemaPL\Auth\Sections\Tokens\Models\PlainToken;
use AwemaPL\Auth\Sections\Tokens\Models\Token;
use AwemaPL\Auth\Sections\Tokens\Repositories\Contracts\TokenRepository;
use AwemaPL\Auth\Sections\Tokens\Repositories\Contracts\UserRepository;
use AwemaPL\Auth\Sections\Tokens\Resources\EloquentToken;
use AwemaPL\Auth\Sections\Installations\Http\Requests\StoreInstallation;
use AwemaPL\Chromator\Sections\Tokens\Http\Requests\ChangeToken;
use AwemaPL\Permission\Repositories\Contracts\PermissionRepository;
use AwemaPL\Permission\Resources\EloquentPermission;
use AwemaPL\Auth\Sections\Tokens\Resources\EloquentUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WidgetController extends Controller
{

    use RedirectsTo;

    /**
     * Tokens repository instance
     *
     * @var TokenRepository
     */
    protected $tokens;

    public function __construct(TokenRepository $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Request show API token
     *
     * @param StoreToken $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function showApiToken(ShowApiTokenWidget $request)
    {
        $tokenId = $request->user()->tokens()->where('name', config('awemapl-auth.default_name_token'))->first()->id;
        return $this->ajaxShowModal('show_api_token','showApiToken', ['api_token'=>PlainToken::where('token_id',$tokenId)->first()->plain_token]);
    }
}
