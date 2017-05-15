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
            if ($this->hasRememberMe()) {
                return $this->loginWithRememberMe();
            }

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
     * Logs on using the information in the coookies.
     *
     * @return Response
     */
    public function loginWithRememberMe()
    {
        $userId = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();
        $user = User::findFirst($userId);

        if ($user) {
            $token = $this->security->getSessionToken();

            if ($cookieToken == $token) {
                // TODO: Implement expiry time on token
                // if ((time() - (86400 * 30)) < $remember->getCreatedAt()) {
                //     if (true === $redirect) {
                //         return $this->response->redirect($pupRedirect->success);
                //     }
                //
                    // return;
                // }

                return $this->respond($user);
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return $this->respondFailedLogin();
    }

    /**
     * Check if the session has a remember me cookie.
     *
     * @return bool
     */
    public function hasRememberMe()
    {
        return $this->cookies->has('RMU');
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

