<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use Phalcon\Mvc\Model;
use RealWorld\Models\User;
use RealWorld\Repository\UserRepository;
use RealWorld\Traits\AuthenticatedUserTrait;
use RealWorld\Transformers\ProfileTransformer;

/**
 * Class ProfileController
 * @package RealWorld\Controllers\Api
 * @property User user
 * @property User $authenticatedUser
 */
class ProfileController extends ApiController
{
    use AuthenticatedUserTrait;

    /**
     * @var UserRepository
     */
    protected $userRepo;

    /**
     *
     */
    public function initialize()
    {
       $this->userRepo = $this->di->getRepository('user');
    }

    /**
     * Get the profile of the user given by their username
     *
     * @param User $user
     * @return Response
     */
    public function showAction($user)
    {
        if (!$user = User::findFirstByUsername($user)) {
            return $this->respondNotFound();
        }

        return $this->respondWithTransformer($user, new ProfileTransformer);
    }

    /**
     * @param $user
     * @return Response
     */
    public function followAction($user)
    {
        $authenticatedUser = $this->getAuthenticatedUser();

        if (!$user = User::findFirstByUsername($user)) {
            return $this->respondNotFound();
        }

        $authenticatedUser->follow($user);

        return $this->respondWithTransformer($user, new ProfileTransformer);
    }

    /**
     * @param $user
     * @return Response
     */
    public function unFollowAction($user)
    {
        $authenticatedUser = $this->getAuthenticatedUser();
        $user = $this->userOrNotFound($user);
        $authenticatedUser->unFollow($user);

        return $this->respondWithTransformer($user, new ProfileTransformer);
    }
}