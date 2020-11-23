<?php

namespace AwemaPL\Auth\Middlewares;

use AwemaPL\Auth\Facades\Auth;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class Installation
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() === 'GET' && Auth::isInstallationUserEnabled() && !Auth::isExistUserInDatabase()){
            $route = Route::getRoutes()->match($request);
            $name = $route->getName();
            if (!in_array($name, config('awemapl-auth.installation.expect.routes'))){
                return redirect()->route('auth.installation.user.register');
            }
        }
        return $next($request);
    }
}