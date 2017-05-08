<?php

namespace RealWorld\Controllers\Api;

use RealWorld\Auth\Auth;

/**
 * Class SessionController
 * @package RealWorld\Controllers\Api
 * @property Auth auth;
 */
class SessionController extends ApiController
{
    public function loginAction()
    {
        try {
            $credentials = $this->request->getJsonRawBody(true)['user'];
            $user = $this->auth->check($credentials);
        } catch (\Exception $e) {
            return $this->respondFailedLogin();
        }

        return $this->respond($user);
    }
}

