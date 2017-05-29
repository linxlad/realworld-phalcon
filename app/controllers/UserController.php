<?php

namespace RealWorld\Controllers;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use RealWorld\Controllers\Api\ApiController;
use RealWorld\Models\User;
use RealWorld\Traits\AuthenticatedUserTrait;
use RealWorld\Transformers\UserTransformer;
use function var_dump;

/**
 * Class UserController
 * @package RealWorld\Controllers\Api
 * @property User user
 */
class UserController extends ApiController
{
    use AuthenticatedUserTrait;

    /**
     * Returns the user stored in the logged in session.
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->respond($this->getUserFromSession());
    }

    /**
     * Update the user based on values.
     *
     * @return Response
     */
    public function updateAction()
    {
        try {
            if (!$input = $this->request->getJsonRawBody(true)['user']) {
                throw new \Exception('No user.');
            }

            $user = $this->getAuthenticatedUser();
            $user->applyInputToModel($input);

            if (!$result = $user->update()) {
                return $this->respondError($user->getMessages());
            }

            $this->updateUserInSession($user);
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }

        return $this->respond($this->getUserFromSession());
    }
}
