<?php

namespace RealWorld\Traits;

use function array_keys;
use function in_array;
use Phalcon\Mvc\Model;
use RealWorld\Models\User;

/**
 * Trait AuthenticatedUserTrait
 * @package RealWorld\Traits
 */
trait AuthenticatedUserTrait
{
    /**
     * @return Model
     */
    protected function getAuthenticatedUser(): Model
    {
        $authenticatedUser = $this->getUserFromSession();

        return $user = User::findFirst([
            "conditions" => "username = ?1",
            "bind"       => [
                1 => $authenticatedUser['username'],
            ]
        ]);
    }

    /**
     * @return mixed
     */
    protected function getUserFromSession(): array
    {
        return $this->session->get('auth') ?? [];
    }

    /**
     * @param Model $user
     * @return array
     */
    protected function updateUserInSession(Model $user): array
    {
        $userFromSession = $this->getUserFromSession();

        foreach ($user->toArray() as $index => $value) {
            if (in_array($index, array_keys($userFromSession))) {
                $userFromSession[$index] = $value;
            }
        }

        $this->session->set('auth', $userFromSession);

        return $userFromSession;
    }
}