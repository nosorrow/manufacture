<?php

namespace App;

class Register
{
    /**
     * Global middleware
     * @var array
     */
    public $middleware = [
       // \App\Middleware\IpChecker::class,
    ];

    /**
     * Route middleware groups
     * @var array
     */
    public $groupMiddleware = [
        'web' => [
       //     \App\Middleware\EncryptCookies::class,
        //     \App\Middleware\Csrf::class,

        ],
    ];
    /**
     * route middleware individual
     * @var array
     */
    public $routeMiddleware = [
        'Auth' => \App\Middleware\Auth::class,
        'Cors'=> \App\Middleware\Cors::class
    ];

    /**
     * Priority-order of non-global Middleware
     * @var array
     */
    public $middlewarePriority = [
        \App\Middleware\Auth::class
    ];
}
