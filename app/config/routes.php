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
use RealWorld\Controllers\UserController;

return [
    [
        'class'   => IndexController::class,
        'methods' => [
            'get' => [
                '/' => 'indexAction',
            ],
        ],
    ],
    [
        'class'   => UserController::class,
        'methods' => [
            'get' => [
                '/user' => 'indexAction',
            ],
            'put' => [
                '/user' => 'updateAction',
            ],
            'patch' => [
                '/user' => 'updateAction',
            ],
        ],
    ],
];
