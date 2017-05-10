<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use RealWorld\Auth\Auth;
use RealWorld\Models\User;

/**
 * Class SessionController
 * @package RealWorld\Controllers\Auth
 * @property Auth auth;
 */
class SessionController extends ApiController
{
    /**
     * @return Response
     */
    public function loginAction()
    {
        try {
            if (!$credentials = $this->request->getJsonRawBody(true)['user']) {
                throw new \Exception('No credentials');
            }

            $user = $this->auth->check($credentials);
        } catch (\Exception $e) {
            return $this->respondFailedLogin();
        }

        return $this->respond($user);
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

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }
    }
}

