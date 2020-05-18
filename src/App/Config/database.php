<?php

return [
    'use-database' => true, // false if you dont use database in your app.

    'default' => 'mysql',
    'connections' => [
        'mysql' =>[
            'driver' => 'mysql',
            'host' => 'localhost',
            'port'=>3306,
            'database' => 'test',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],
        'mysql-xampp' =>[
            'driver' => 'mysql',
            'host' => 'localhost',
            'port'=>33060,
            'database' => 'tod',
            'username' => 'root',
            'password' => 'secret',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]
    ]
];
