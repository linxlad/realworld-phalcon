<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use RealWorld\Models\User;
use RealWorld\Transformers\ProfileTransformer;

/**
 * Class ProfileController
 * @package RealWorld\Controllers\Api
 * @property User user
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
        $user = User::findFirst([
            "conditions" => "username = ?1",
            "bind"       => [
                1 => trim($user),
            ]
        ]);

        return $this->respondWithTransformer($user, new ProfileTransformer);
    }

    public function updateAction()
    {
        try {
            if (!$userInput = $this->request->getJsonRawBody(true)['user']) {
                throw new \Exception('No user');
            }

            $user = $this->request->user;

            foreach ($userInput as $field=>$value) {
                $user->$field = $value;
            }

            if (!$result = $user->update()) {
                return $this->respondError($user->getMessages());
            }
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }

        return $this->respond($user);
    }
}