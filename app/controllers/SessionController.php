<?php

namespace RealWorld\Controllers;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use RealWorld\Auth\Auth;
use RealWorld\Models\User;
use RealWorld\Transformers\UserTransformer;

/**
 * Class SessionController
 * @package RealWorld\Controllers\Auth
 * @property Auth auth
 */
class SessionController extends Controller //extends ApiController
{
    /**
     * @return Response
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

            $user = $this->auth->check($credentials);
        } catch (\Exception $e) {
            return $this->respondFailedLogin();
        }

        return $this->respondWithTransformer($user, new UserTransformer);
    }

    /**
     * @return Response
     */
    public function registerAction()
    {
        try {
            if (!$userInput = $this->request->getJsonRawBody(true)['user']) {
                throw new \Exception('No user');
            }

            $user = new User();

            if (!$result = $user->create($userInput, array_keys($userInput))) {
                return $this->respondError($user->getMessages());
            }

            return $this->respondWithTransformer($result, new UserTransformer);
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }
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

