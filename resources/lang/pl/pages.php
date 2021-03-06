<?php

return [
    'register' => [
        'headline' => 'Zarejestruj swoje konto',
        'meta_title'=> 'Zarejestruj swoje konto',
        'meta_description' => 'Rejestracja nowego konta w systemie',
        'footer-headline' => 'Masz już swoje konto? <a href=":link_url">:link_name</a>',
        'sign_in' => 'Zaloguj się',
    ],
    'login' => [
        'headline' => 'Logowanie',
        'meta_title'=> 'Logowanie do konta',
        'meta_description' => 'Logowanie użytkownika do swojego konta',
        'footer-headline' => 'Nie masz konta? <a href=":link_url">:link_name</a>',
        'sign_up' => 'Zarejestruj się',
    ],
    'verify' => [
        'headline' => 'Weryfikacja Twojego adresu Email',
        'meta_title'=> 'Weryfikacja Twojego adresu Email',
        'meta_description' => 'Weryfikacja Twojego adresu Email do pełnego dostępu w systemie',
        'footer-headline' => '<a href=":link_url" class="awema-spa-ignore">:link_name</a>',
        'logout' => 'Wyloguj się',
    ],
    'resent' => [
        'success_sent_link_email' => 'Pomyślnie wysłano email z linkiem',
    ],
    'password' =>[
        'email' => [
            'headline' => 'Resetowanie hasła',
            'meta_title'=> 'Resetowanie hasła',
            'meta_description' => 'Resetowanie hasła użytkownika do systemu',
            'footer-headline' => '<a href=":link_url">:link_name</a>',
            'back_to_sign_in' => 'Powrót do logowania',
        ],
        'reset' => [
            'headline' => 'Resetowanie hasła',
            'meta_title'=> 'Resetowanie hasła',
            'meta_description' => 'Resetowanie hasła użytkownika do systemu',
        ]
    ],
    'installation' => [
        'user' =>[
            'headline' => 'Instalacja pierwszego użytkownika',
            'meta_title'=> 'Instalacja pierwszego użytkownika',
            'meta_description' => 'Instalacja pierwszego użytkownika do systemu',
        ]
    ],
    'user' => [
        'headline' => 'Użytkownicy',
        'meta_title'=> 'Użytkownicy',
        'meta_description' => 'Użytkownicy aplikacji',
        'users' => 'Użytkownicy',
        'email_verified' => 'Zweryfikowany e-mail',
        'yes' => 'Tak',
        'no' => 'Nie',
        'options' => 'Opcje',
        'created_at' =>'Utworzono',
        'create_user' => 'Utwórz użytkownika',
        'edit_user' => 'Edycja użytkownika',
        'change_password' => 'Zmiana hasła',
        'name' =>'Nazwa',
        'email' =>'E-mail',
        'password' =>'Hasło',
        'confirm_password' =>'Potwierdź hasło',
        'edit' =>'Edytuj',
        'delete' =>'Usuń',
        'are_you_sure_delete'=>'Czy na pewno usunąć?',
        'confirm' =>'Potwierdź',
        'save' => 'Zapisz',
        'switch_user' =>'Przełącz na użytkownika',
        'are_you_sure_switch'=>'Czy na pewno przełączyć?',
    ],
    'token' => [
        'meta_title' => 'Tokeny API',
        'headline' => 'Tokeny API',
        'meta_description' => 'Tokeny API w aplikacji',
        'tokens' => 'Tokeny API',
        'user' => 'Użytkownik',
        'searching' => 'Wyszukiwanie',
        'name' => 'Nazwa',
        'create_token' => 'Utwórz token',
        'last_used_at' => 'Ostatnie użycie',
        'created_at' => 'Utworzono',
        'updated_at' => 'Zaktualizowano',
        'create_token' => 'Utwórz token',
        'show_token'=> 'Pokaż token',
        'change_token' => 'Zmień token',
        'select_user' => 'Wybierz użytkownika',
        'token' => 'Token',
        'options' => 'Opcje',
        'create' => 'Utwórz',
        'confirm' => 'Potwierdź',
        'close' => 'Zamknij',
        'delete_token'=>'Usuń token',
        'are_you_sure_change' => 'Czy na pewno zmienić?',
        'are_you_sure_delete' => 'Czy na pewno usunąć?',
    ],
    'widget'=>[
        'token' =>[
            'api_token' =>'Token API',
            'your_api_token_for_extension' =>'Twój token dostępu do API dla <a href=":url" target="_blank">rozszerzenia Chrome</a>.',
            'show_api_token' =>'Pokaż token API',
            'show' =>'Pokaż',
            'enter_login_password' =>'Proszę podać hasło do logowania',
            'your_api_token' =>'Twój token API',
            'copy_to_clipboard' =>'Skopiuj do schowka',
            'copied_to_clipboard' =>'Skopiowano do schowka.'
        ]
    ]
];