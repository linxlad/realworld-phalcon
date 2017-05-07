<?php

namespace RealWorld\Auth;

use Phalcon\Mvc\User\Component;
use RealWorld\Models\Users;

class Auth extends Component
{
    /**
     * @param array $credentials
     * @return bool
     * @throws \Exception
     */
    public function check(array $credentials)
    {
        // First check if the user exists.
        $user = Users::findFirstByEmail($credentials['email']);

        if (!$user) {
            throw new \Exception('Wrong email/password combination');
        }

        // Check the password matches the one saves to the user.
        if (!$this->security->checkHash($credentials['password'], $user->password)) {
            throw new \Exception('Wrong email/password combination');
        }

        return true;
    }
}