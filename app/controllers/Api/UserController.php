<?php

namespace RealWorld\Controllers\Api;

use RealWorld\Models\User;

/**
 * Class UserController
 * @package RealWorld\Controllers\Api
 * @property User user
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

            $user = $this->request->user;
            $user = User::findFirst(4)->validation();

            foreach ($userInput as $field=>$value) {
                $user->$field = $value;
            }

            if (!$result = $user->save()) {
                return $this->respondError($user->getMessages());
            }
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }
    }
}