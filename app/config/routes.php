<?php

/**
 * This file contains all the routes available by the application. It is
 * parsed by the Bootstrap class and the relevant endpoints are registered
 * with the application
 *
 * The array contains one element per route.
 *
 * Each element contains the class name that will be handling the route if it
 * matches and a 'methods' element.
 *
 * The 'methods' element contains keys as the verbs (get, post, put etc.) and
 * as value again a key/value pair array. This sub array maps the actual
 * endpoint with the action in the handler class.
 */

use RealWorld\Controllers\IndexController;
use RealWorld\Controllers\SessionController;
use RealWorld\Controllers\UserController;
//$apiGroup->add('/users/login', 'Session::login', ['POST', 'OPTIONS']);
return [
    [
        'class'   => IndexController::class,
        'methods' => [
            'get' => [
                '/' => 'index',
            ],
        ],
    ],
    [
        'class'   => SessionController::class,
        'methods' => [
            'post' => [
                '/api/users/login' => 'login',
                '/api/users' => 'register',
            ],
        ],
    ],
    [
        'class'   => UserController::class,
        'methods' => [
            'get' => [
                '/api/user' => 'index',
            ],
            'put' => [
                '/api/user' => 'update',
            ],
            'patch' => [
                '/api/user' => 'update',
            ],
        ],
    ],
];
