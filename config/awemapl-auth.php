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

    /*
    |--------------------------------------------------------------------------
    | Installation application.
    |--------------------------------------------------------------------------
    */
    'installation' => [
        // Add first user - section `user`
        'sections' => ['user'],

        // except for redirect to installation
        'expect' => [

            'routes' => [
                'register',
                'module-assets.assets',
                'auth.installation.user.register'
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Verification e-mail users.
    |--------------------------------------------------------------------------
    */
    'verification' => [
        // except for redirect to verification
        'expect' => [

            'routes' => [
                'verification.notice',
                'module-assets.assets',
                'verification.verify',
                'verification.resend',
                'logout',
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel application tables
    |--------------------------------------------------------------------------
    */
    'tables' =>[
        'users' => 'users',
        'plain_tokens' =>'auth_plain_tokens'
    ],

    /*
    |--------------------------------------------------------------------------
    | Additional routes in Auth module
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'user' => [
            'active' => true,
            'prefix' => '/admin/auth/users',
            'name_prefix' => 'auth.user.',
            'middleware' => [
                'web',
                'auth',
                'can:manage_users'
            ]
        ],
        'token' => [
            'active' => true,
            'prefix' => '/admin/auth/tokens',
            'name_prefix' => 'auth.token.',
            'middleware' => [
                'web',
                'auth',
                'can:manage_tokens'
            ]
        ],
        'widget'=>[
            'token' => [
                'active' => true,
                'prefix' => '/widget/auth/tokens',
                'name_prefix' => 'widget.auth.token.',
                'middleware' => [
                    'web',
                    'auth',
                ]
            ],
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Use permissions in application.
    |--------------------------------------------------------------------------
    |
    | This permission has been insert to database with migrations
    | of module permission.
    |
    */
    'permissions' =>[
        'manage_tokens','manage_users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Can merge permissions to module permission
    |--------------------------------------------------------------------------
    */
    'merge_permissions' => true,


    /*
    |--------------------------------------------------------------------------
    | Default name token
    |--------------------------------------------------------------------------
    */
    'default_name_token' => 'global',
];
