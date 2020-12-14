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

    protected function ajaxNotifySuccess($message, $status =200)
    {
        return $this->ajax([
            'status' =>'success',
            'message' => $message
        ], $status);
    }

    protected function ajaxNotifyError($message, int $status, $code = '', $data = [])
    {
        $data = array_merge([
            'status' =>'error',
            'message' => $message,
            'code' => $code,
        ], $data);
        return $this->ajax($data, $status);
    }

    protected function ajax($data, $status =200){
        return response()->json($data, $status, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);
    }

    protected function ajaxRedirectTo($request)
    {
        return $this->ajaxRedirectToUrl(redirect()
            ->intended($this->getRedirectToUrl())
            ->getTargetUrl());
    }

    protected function ajaxRedirectToUrl($url)
    {
        return $this->ajax([
            'redirectUrl' => $url
        ]);
    }

    protected function ajaxShowModal($modalName, $storeDataParam, $storeData)
    {
        return $this->ajax([
            'showModal' => [
                'modalName' => $modalName,
                'storeDataParam' => $storeDataParam,
                'storeData' => $storeData,
            ]
        ]);
    }

    protected function ajaxSetData($storeDataParam, $storeData)
    {
        return $this->ajax([
            'setData' => [
                'storeDataParam' => $storeDataParam,
                'storeData' => $storeData,
            ]
        ]);
    }

    protected function getRedirectToUrl()
    {
        $url = $this->redirectMappings[get_class($this)] ?? null;
        if (!$url){
            return '/';
        }
        return config('awemapl-auth.redirects.' .$url) ;
    }
}
