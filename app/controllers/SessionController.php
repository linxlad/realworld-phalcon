<?php

namespace RealWorld\Controllers;

use RealWorld\Auth;
use RealWorld\Controllers\Api\ApiController;
use RealWorld\Models\User;
use RealWorld\Traits\AuthenticatedUserTrait;
use RealWorld\Traits\ResponseErrorTrait;

/**
 * Class SessionController
 * @package RealWorld\Controllers\Auth
 * @property Auth auth
 */
class SessionController extends ApiController
{
    use AuthenticatedUserTrait, ResponseErrorTrait;

    /**
     * Handle a login request to the application.
     *
     * @return \Phalcon\Http\Response
     */
    public function loginAction()
    {
        try {
            if ($this->hasRememberMe() && ($user = $this->auth->loginWithRememberMe())) {
                return $this->respond($user);
            }

            if (!$credentials = $this->request->getJsonRawBody(true)['user']) {
                throw new \Exception('No credentials');
            }

            $this->auth->check($credentials);
        } catch (\Exception $e) {
            return $this->respondFailedLogin();
        }

        return $this->respond($this->getUserFromSession());
    }

    /**
     * Handle a registration request for the application.
     */
    public function registerAction()
    {
        try {
            if (!$userInput = $this->request->getJsonRawBody(true)['user']) {
                throw new \Exception('No user');
            }

            $user = new User();

            if (!$result = $user->create($userInput, array_keys($userInput))) {
                $this->respondError($user->getMessages());
            }
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }

        return $this->respond([
            'email'     => $result->email,
            'username'  => $result->username,
            'bio'       => $result->bio,
            'image'     => $result->image,
            'token'     => $result->token,
        ]);
    }

    /**
     * Check if the session has a remember me cookie.
     *
     * @return bool
     */
    private function hasRememberMe()
    {
        return $this->cookies->has('RMU');
    }
}

