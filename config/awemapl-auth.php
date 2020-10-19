<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled auth types & services: 'social', 'two_factor'
    |--------------------------------------------------------------------------
    */
    'enabled' => [
//        'social',
//        'two_factor',
        'email_verification'
    ],

    /*
    |--------------------------------------------------------------------------
    | Socialite related parameters
    |--------------------------------------------------------------------------
    */
    'socialite' => [

        /*
        |--------------------------------------------------------------------------
        | Available socialite services
        |--------------------------------------------------------------------------
        */
        'services' => [
            'github' => [
                'name' => 'GitHub'
            ]
        ],

        /*
        |--------------------------------------------------------------------------
        | Services credentials
        |--------------------------------------------------------------------------
        */
        'github' => [
            'client_id' => env('GITHUB_CLIENT_ID'),
            'client_secret' => env('GITHUB_CLIENT_SECRET'),
            'redirect' => env('GITHUB_REDIRECT_URL'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Two factor authentication parameters
    |--------------------------------------------------------------------------
    */
    'two_factor' => [

        'authy' => [
            'secret' => env('AUTHY_SECRET')
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Redirects
    |--------------------------------------------------------------------------
    */
    'redirects' => [
        'login' => '/home',
        'register' => '/email/verify',
        'reset_password' => '/login',
        'social_login' => '/',
        'twofactor_login' => '/',
        'email_verification' => '/home',
        'forgot_password' => '/password/reset',
        'twofactor' => '/',
    ],

    'mailables' => [
         'email_verification' => AwemaPL\Auth\Mail\MailMessage::class,
         'reset_password' => AwemaPL\Auth\Mail\MailMessage::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | With registration form
    |--------------------------------------------------------------------------
    */
    'with_register' => true,
];
