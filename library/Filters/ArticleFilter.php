<?php

namespace RealWorld\Filters;

use RealWorld\Models\User;
use RealWorld\Repository\ArticleRepository;

/**
 * Class ArticleFilter
 * @package RealWorld\Filters
 */
class ArticleFilter extends ArticleRepository
{
    /**
     * @param $username
     * @return bool|\Phalcon\Mvc\Model
     */
    protected function author($username)
    {
        if (!$user = User::findByUsername($username)) {
            return false;
        }

        return $this->firstBy(['userId' => $user->id]);
    }

    protected function favorited($username)
    {
        if (!$user = User::findByUsername($username)) {
            return false;
        }
    }

    protected function tag($name)
    {
        if (!$user = User::findByUsername($username)) {
            return false;
        }
    }
}