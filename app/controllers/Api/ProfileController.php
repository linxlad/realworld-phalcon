<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use Phalcon\Mvc\Model;
use RealWorld\Models\User;
use RealWorld\Repository\UserRepository;
use RealWorld\Transformers\ProfileTransformer;

/**
 * Class ProfileController
 * @package RealWorld\Controllers\Api
 * @property User user
 * @property User $authenticatedUser
 */
class ProfileController extends ApiController
{
    /**
     * Get the profile of the user given by their username
     *
     * @param User $user
     * @return Response
     */
    public function showAction($user)
    {
        $user = $this->findByUserName($user);

        return $this->respondWithTransformer($user, new ProfileTransformer);
    }

    /**
     * @param $user
     * @return Response
     */
    public function followAction($user)
    {
        $authenticatedUser = $this->request->user;
        $user = $this->findByUserName($user);
        $authenticatedUser->follow($user);

        return $this->respondWithTransformer($user, new ProfileTransformer);
    }

    /**
     * @param $user
     * @return Response
     */
    public function unFollowAction($user)
    {
        $authenticatedUser = $this->request->user;
        $user = $this->findByUserName($user);
        $authenticatedUser->unFollow($user);

        return $this->respondWithTransformer($user, new ProfileTransformer);
    }

    /**
     * @param $username
     * @return Model
     */
    private function findByUserName($username)
    {
        $userRepo = new UserRepository();
        return $userRepo->firstBy(['username' => trim($username)]);
    }
}