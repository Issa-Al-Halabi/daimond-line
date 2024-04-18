<?php
/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */
return [
    'default' => env('MAIL_MAILER', 'smtp'),

    'mailers' => [
        // 'smtp' => [

        //     'smtp' => [
        //         'transport' => 'smtp',
        //         'host' => env('MAIL_HOST', 'myserver.myhost.com'),
        //         'port' => env('MAIL_PORT', 465),
        //         'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        //         'username' => env('mail@mydomain.com'),
        //         'password' => env('mypassword'),
        //         'timeout' => null,
        //         'auth_mode' => null,
        //     ],
        // ],
    ],
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Model\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'firebase' => [

        'database_url' => env('db_url'),
        'secret' => env('db_secret'),

    ],

];
