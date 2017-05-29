<?php

/**
 * This file contains all the middleware declarations.
 *
 * The array contains one middleware declaration per element
 *
 * Each element contains the event that the middleware will be bound to in our
 * application "after", "before" and "finish" are available events.
 *
 * The class key contains the name of the middleware class to be used.
 */

use RealWorld\Middleware\JWTAuthenticationMiddleware;
use RealWorld\Middleware\ResponseMiddleware;

return [
    [
        'event' => 'before',
        'class' => JWTAuthenticationMiddleware::class,
    ],
    [
        'event' => 'after',
        'class' => ResponseMiddleware::class,
    ],
];
