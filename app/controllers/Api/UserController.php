<?php

namespace RealWorld\Controllers\Api;

use RealWorld\Models\User;

/**
 * Class UserController
 * @package RealWorld\Controllers\Api
 */
class UserController extends ApiController
{
    /**
     * @return string
     */
    public function indexAction()
    {
        return '{user: {name: Nathan}}';
    }

    public function updateAction()
    {
        try {
            if (!$userInput = $this->request->getJsonRawBody(true)['user']) {
                throw new \Exception('No user');
            }

            $user = new User();

            if (!$result = $user->create($userInput, array_keys($userInput))) {
                return $this->respondError($user->getMessages());
            }

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }
    }
}