<?php

namespace RealWorld\Controllers\Api;

/**
 * Class UserController
 * @package RealWorld\Controllers\Api
 */
class UserController extends ApiController
{
    public function indexAction()
    {
        echo '{user: {name: Nathan}}';
    }
}