<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the Router within a group which
| is assigned the "api" namespace.
|
*/

use \Phalcon\Mvc\Router\Group as RouterGroup;

$router = $di->getRouter();

$apiGroup = new RouterGroup([
    'namespace' => 'RealWorld\\Controllers\\Api'
]);
$apiGroup->setPrefix('/api');

// Authentication routes.
$apiGroup->add('/users/login', 'Session::login', ['POST', 'OPTIONS']);
$apiGroup->add('/users', 'Session::register', ['POST', 'OPTIONS']);

// User routes.
$apiGroup->add('/user', 'User::index', ['GET', 'OPTIONS']);
$apiGroup->add('/user', 'User::update', ['PUT', 'PATCH', 'OPTIONS']);

// Profile routes.
$apiGroup->add('/profiles/{user}', 'Profile::show', ['GET', 'OPTIONS']);
$apiGroup->add('/profiles/{user}/follow', 'Profile::follow', ['POST', 'OPTIONS']);
$apiGroup->add('/profiles/{user}/follow', 'Profile::unFollow', ['DELETE', 'OPTIONS']);

// Article routes.
$apiGroup->add('/articles/feed', 'Feed::index', ['GET', 'OPTIONS']);
$apiGroup->add('/articles/{article}/favorite', 'Favorite::add', ['POST', 'OPTIONS']);
$apiGroup->add('/articles/{article}/favorite', 'Favorite::remove', ['DELETE', 'OPTIONS']);

$apiGroup->add('/articles', 'Article::index', ['GET', 'OPTIONS']);
$apiGroup->add('/articles', 'Article::create', ['POST', 'OPTIONS']);
$apiGroup->add('/articles', 'Article::update', ['PUT', 'PATCH', 'OPTIONS']);
$apiGroup->add('/articles', 'Article::delete', ['DELETE', 'OPTIONS']);

// Comment routes.

// Tag routes.
$apiGroup->add('/tags', 'Tag::index', ['GET', 'OPTIONS']);
$router->mount($apiGroup);
$router->handle();
