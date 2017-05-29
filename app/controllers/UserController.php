<?php

namespace RealWorld\Controllers;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use RealWorld\Models\User;
use RealWorld\Transformers\UserTransformer;

/**
 * Class UserController
 * @package RealWorld\Controllers\Api
 * @property User user
 */
class UserController extends Controller //extends ApiController
{
//    /**
//     * @var User
//     */
//    protected $authenticatedUser;
//
//    /**
//     *
//     */
//    public function initialize()
//    {
//        // Make sure the request does have a user (shouldn't get this far).
//        if (!$this->request->user) {
//            return $this->respondForbidden();
//        }
//
//        $this->authenticatedUser = $this->request->user;
//    }
//
    /**
     * @return Response
     */
    public function indexAction()
    {
        echo 'asdasd'; exit;
        $this->response->setJsonContent(
            [
                'email',
                'username',
                'bio',
                'image',
                'token',
            ]
        );


//        return $this->respondWithTransformer(
//            $this->authenticatedUser,
//            new UserTransformer
//        );
    }
//
//    /**
//     * @return Response
//     */
//    public function updateAction()
//    {
//        try {
//            if (!$input = $this->request->getJsonRawBody(true)['user']) {
//                throw new \Exception('No user.');
//            }
//
//            $user = $this->authenticatedUser;
//            $user->applyInputToModel($input);
//
//            if (!$result = $user->update()) {
//                return $this->respondError($user->getMessages());
//            }
//        } catch (\Exception $e) {
//            return $this->respondError($e->getMessage());
//        }
//
//        return $this->respondWithTransformer($user, new UserTransformer);
//    }
}
