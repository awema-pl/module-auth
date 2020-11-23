<?php

namespace AwemaPL\Auth\Sections\Users\Http\Controllers;

use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Auth\Sections\Users\Http\Requests\ChangePasswordUser;
use AwemaPL\Auth\Sections\Users\Http\Requests\StoreUser;
use AwemaPL\Auth\Sections\Users\Http\Requests\UpdateUser;
use AwemaPL\Auth\Sections\Users\Models\User;
use AwemaPL\Auth\Sections\Users\Repositories\Contracts\UserRepository;
use AwemaPL\Auth\Sections\Users\Resources\EloquentUser;
use AwemaPL\Auth\Sections\Installations\Http\Requests\StoreInstallation;
use AwemaPL\Permission\Repositories\Contracts\PermissionRepository;
use AwemaPL\Permission\Resources\EloquentPermission;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{

    /**
     * Users repository instance
     *
     * @var UserRepository
     */
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Display users
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('awemapl-auth::sections.users.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentUser::collection(
            $this->users->scope($request)
                ->latest()->smartPaginate()
        );
    }

    /**
     * Create user
     *
     * @param StoreUser $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(StoreUser $request)
    {
        $request->merge([
            'password' => Hash::make($request->password),
        ]);
        $user = $this->users->create($request->all());
        event(new Registered($user));
        return notify(_p('auth::notifies.users.success_created_user', 'Success created user.'));
    }

    /**
     * Update user
     *
     * @param UpdateUser $request
     * @param $id
     * @return array
     */
    public function update(UpdateUser $request, $id)
    {
        $this->users->update($request->only(['name', 'email_verified_at']), $id);
        return notify(_p('auth::notifies.users.success_updated_user', 'Success updated user.'));
    }

    /**
     * Change password user
     *
     * @param ChangePasswordUser $request
     * @param $id
     * @return array
     */
    public function changePassword(ChangePasswordUser $request, $id)
    {
        $request->merge([
            'password' => Hash::make($request->password),
        ]);
        $this->users->update($request->only(['password']), $id);
        return notify(_p('auth::notifies.users.success_changed_password_user', 'Success changed password user.'));
    }

    /**
     * Delete user
     *
     * @param $id
     * @return array
     */
    public function delete($id)
    {
        $this->users->delete($id);

        return notify(_p('auth::notifies.users.success_deleted_user', 'Success deleted user.'));
    }
}
