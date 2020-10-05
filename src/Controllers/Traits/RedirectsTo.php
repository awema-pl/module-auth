<?php

namespace AwemaPL\Auth\Controllers\Traits;

trait RedirectsTo
{
    /**
     * Mappings between classes and config's keys
     *
     * @var array
     */
    protected $redirectMappings = [
        'AwemaPL\Auth\Controllers\LoginController' => 'login',
        'AwemaPL\Auth\Controllers\RegisterController' => 'register',
        'AwemaPL\Auth\Controllers\ResetPasswordController' => 'reset_password',
        'AwemaPL\Auth\Controllers\SocialLoginController' => 'social_login',
        'AwemaPL\Auth\Controllers\TwoFactorLoginController' => 'twofactor_login',
        'AwemaPL\Auth\Controllers\VerificationController' => 'email_verification',
        'AwemaPL\Auth\Controllers\ForgotPasswordController' => 'forgot_password',
        'AwemaPL\Auth\Controllers\TwoFactorController' => 'twofactor',
    ];

    /**
     * Redirect to path which set in config or to default one
     *
     * @return string
     */
    protected function redirectTo()
    {
        return $this->getRedirectToUrl()
            ?: (property_exists($this, 'redirectTo') ? $this->redirectTo : '/');
    }

    protected function ajaxRedirectTo($request)
    {
        return response()->json([
            'redirectUrl' => redirect()
                ->intended($this->getRedirectToUrl())
                ->getTargetUrl()
        ]);
    }

    protected function getRedirectToUrl()
    {
        return config('awemapl-auth.redirects.' . $this->redirectMappings[get_class($this)]);
    }
}
