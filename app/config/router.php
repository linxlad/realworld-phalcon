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
$apiGroup->addPost('/users/login', 'Auth::login');
$apiGroup->addPost('/users', 'Auth::register');

// User routes.
$apiGroup->addGet('/user', 'User::index');
$apiGroup->addPut('/user', 'User::update');
$apiGroup->addPatch('/user', 'User::index');

// Profile routes.
$apiGroup->addGet('/profiles/{user}', 'Profile::show');
$apiGroup->addPost('/profiles/{user}/follow', 'Profile::follow');
$apiGroup->addDelete('/profiles/{user}/follow', 'Profile::unFollow');

// Article routes.
$apiGroup->addGet('/articles/feed', 'Feed::index');
$apiGroup->addPost('/articles/{article}/favorite', 'Favorite::add');
$apiGroup->addDelete('/articles/{article}/favorite', 'Favorite::remove');

// Comment routes.

// Tag routes.
$apiGroup->addGet('/tags', 'Tag::index');

$router->mount($apiGroup);
$router->handle();