<?php

namespace RealWorld\Auth;

use Firebase\JWT\JWT;
use Phalcon\Mvc\User\Component;
use Phalcon\Security\Exception;
use RealWorld\Models\User;

/**
 * Class Auth
 * @package RealWorld\Auth
 * @property User user
 */
class Auth extends Component
{
    /**
     * @param array $credentials
     * @return User
     * @throws \Exception
     */
    public function check(array $credentials)
    {
        // First check if the user exists.
        $user = User::findFirst([
            "conditions" => "email = ?1",
            "bind"       => [
                1 => $credentials['email'],
            ]
        ]);

        if (!$user) {
            throw new \Exception('Wrong email/password combination');
        }

        // Check the password matches the one saves to the user.
        if (!$this->security->checkHash($credentials['password'], $user->password)) {
            throw new \Exception('Wrong email/password combination');
        }

        if (isset($credentials['remember'])) {
            $this->createRememberMe($user);
        }

        try {
            // Re-issue JWT if expired.
            $key = $this->di->get('config')->application->security->salt;
            JWT::decode($user->token, $key, ['HS256']);
        } catch (\Firebase\JWT\ExpiredException $e) {
            $user->token = $user->generateJWT();
            $user->update();
        }

        return $user;
    }


    /**
     * Creates the remember me environment settings the related cookies and generating tokens.
     *
     * @param User $user
     */
    public function createRememberMe($user)
    {
        $token = $this->security->getToken();
        $expire = time() + 86400 * 30;
        $this->cookies->set('RMU', $user->getId(), $expire);
        $this->cookies->set('RMT', $token, $expire);

//        $remember = new UserRememberTokens();
//        $remember->setUserId($user->getId());
//        $remember->setToken($token);
//        $remember->setUserAgent($user_agent);
//        $remember->setCreatedAt(time());
//
//        if ($remember->save() != false) {
//            $expire = time() + 86400 * 30;
//            $this->cookies->set('RMU', $user->getId(), $expire);
//            $this->cookies->set('RMT', $token, $expire);
//         }
    }

    /**
     * Logs on using the information in the coookies.
     *
     * @return User|bool
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

                return $user;
            }
        }

        $this->removeRememberCookies();

        return false;
    }

    /**
     * @param $token
     * @return User
     * @throws Exception
     */
    public function loginWithJWT($token)
    {
        $user = User::findFirst([
            "conditions" => "username = ?1",
            "bind"       => [
                1 => $token->id,
            ]
        ]);

        return $user;
    }

    /**
     *
     */
    private function removeRememberCookies()
    {
        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();
    }
}