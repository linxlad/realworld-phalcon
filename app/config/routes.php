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
use RealWorld\Controllers\Api\ArticleController;
use RealWorld\Controllers\Api\FavoriteController;
use RealWorld\Controllers\Api\FeedController;
use RealWorld\Controllers\Api\CommentController;
use RealWorld\Controllers\Api\ProfileController;
use RealWorld\Controllers\Api\TagController;

return [
    [
        'class'   => IndexController::class,
        'methods' => [
            'get' => [
                '/' => 'index',
            ],
        ],
    ],

    // Authentication routes.
    [
        'class'   => SessionController::class,
        'methods' => [
            'post' => [
                '/api/users/login' => 'login',
                '/api/users' => 'register',
            ],
        ],
    ],

    // User routes.
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

    // Profile routes.
    [
        'class'   => ProfileController::class,
        'methods' => [
            'get' => [
                '/api/profiles/{user}' => 'show',
            ],
            'post' => [
                '/api/profiles/{user}/follow' => 'follow',
            ],
            'delete' => [
                '/api/profiles/{user}/follow' => 'unFollow',
            ],
        ],
    ],

    // Article routes.

//    $apiGroup->add(
//        '/articles/:params',
//        [
//            'controller' => 'Article',
//            'action' => 'index',
//            'params' => 1
//        ],
//        ['GET', 'OPTIONS']
//    );

    [
        'class'   => FeedController::class,
        'methods' => [
            'get' => [
                '/api/articles/feed' => 'index',
            ],
        ],
    ],
    [
        'class'   => FavoriteController::class,
        'methods' => [
            'post' => [
                '/api/articles/{article}/favorite' => 'add',
            ],
            'delete' => [
                '/api/articles/{article}/favorite' => 'remove',
            ],
        ],
    ],
    [
        'class'   => ArticleController::class,
        'methods' => [
            'get' => [
                '/api/articles/{article}' => 'index',
            ],
            'post' => [
                '/api/articles' => 'create',
            ],
            'put' => [
                '/api/articles/{article}' => 'update',
            ],
            'patch' => [
                '/api/articles/{article}' => 'update',
            ],
            'delete' => [
                '/api/articles/{article}' => 'delete',
            ],
        ],
    ],

    // Comment routes.
    [
        'class'   => CommentController::class,
        'methods' => [
            'get' => [
                '/api/articles/{article}/comments' => 'index',
            ],
            'post' => [
                '/api/articles/{article}/comments' => 'add',
            ],
            'delete' => [
                '/api/articles/{article}/comments/{id}' => 'delete',
            ],
        ],
    ],

    // Tag routes.
    [
        'class'   => TagController::class,
        'methods' => [
            'get' => [
                '/api/tags' => 'index',
            ],
        ],
    ],
];
