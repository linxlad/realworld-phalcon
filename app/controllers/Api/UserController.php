<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use RealWorld\Models\User;
use RealWorld\Transformers\UserTransformer;

/**
 * Class UserController
 * @package RealWorld\Controllers\Api
 * @property User user
 */
class UserController extends ApiController
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->respondWithTransformer(
            $this->request->user,
            new UserTransformer
        );
    }

    /**
     * @return Response
     */
    public function updateAction()
    {
        try {
            if (!$userInput = $this->request->getJsonRawBody(true)['user']) {
                throw new \Exception('No user.');
            }

            $user = $this->request->user;

            foreach ($userInput as $field => $value) {
                $user->$field = $value;
            }

            if (!$result = $user->update()) {
                return $this->respondError($user->getMessages());
            }
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }

        return $this->respondWithTransformer($user, new UserTransformer);
    }
}