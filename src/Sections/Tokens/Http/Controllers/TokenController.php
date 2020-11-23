<?php

namespace AwemaPL\Auth\Sections\Tokens\Http\Controllers;

use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Auth\Sections\Tokens\Http\Requests\StoreToken;
use AwemaPL\Auth\Sections\Tokens\Http\Requests\UpdateToken;
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

class TokenController extends Controller
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
     * Display tokens
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('awemapl-auth::sections.tokens.index');
    }

    /**
     * Request store
     *
     * @param StoreToken $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function store(StoreToken $request)
    {
        $plainToken = $this->tokens->create($request->all());
        return $this->ajaxShowModal('show_token', 'showToken', ['plain_token'=>$plainToken]);
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentToken::collection(
            $this->tokens->scope($request)
                ->latest()->smartPaginate()
        );
    }

    /**
     * Change token
     *
     * @param Request $request
     * @param $id
     * @return array
     */
    public function change(Request $request, $id)
    {
        $plainToken = $this->tokens->change($id);
        return $this->ajaxShowModal('show_token', 'showToken', ['plain_token'=>$plainToken]);
    }

    /**
     * Delete token
     *
     * @param Request $request
     * @param $id
     * @return array
     */
    public function delete(Request $request, $id)
    {
        $this->tokens->delete($id);
        return notify(_p('auth::notifies.tokens.success_deleted_token', 'Success deleted token.'));
    }
}
