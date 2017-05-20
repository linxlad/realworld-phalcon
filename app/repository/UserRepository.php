<?php

namespace RealWorld\Repository;

use RealWorld\Models\User;

/**
 * Class UserRepository
 * @package RealWorld\Repository
 */
class UserRepository extends Repository
{
    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * @return string
     */
    public function getModelName()
    {
        return User::class;
    }
}