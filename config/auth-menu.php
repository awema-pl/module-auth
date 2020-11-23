<?php
return [
    'merge_to_navigation' => true,
    'navs' => [
        'sidebar' => [],
        'adminSidebar' => [
            [
                'name' => 'Authorizations',
                'link' => '/admin/auth/users',
                'icon' => 'speed',
                'permissions' => 'manage_users',
                'key' => 'auth::menus.authorizations',
                'children_top' => [
                    [
                        'name' => 'Users',
                        'link' => '/admin/auth/users',
                        'key' => 'auth::menus.users',
                    ],
                    [
                        'name' => 'API tokens',
                        'link' => '/admin/auth/tokens',
                        'key' => 'auth::menus.tokens',
                    ]
                ],
                'children' => [
                    [
                        'name' => 'Users',
                        'link' => '/admin/auth/users',
                        'key' => 'auth::menus.users',
                    ],
                    [
                        'name' => 'API tokens',
                        'link' => '/admin/auth/tokens',
                        'key' => 'auth::menus.tokens',
                    ]
                ],
            ]
        ]
    ]
];
