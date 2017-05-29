<?php

namespace RealWorld\Traits;

use Phalcon\Http\Response;

/**
 * Trait ResponseErrorTrait
 *
 * @property Response $response
 */
trait ResponseErrorTrait
{
    /**
     * Respond with json error message.
     *
     * @param $message
     */
    protected function respondError($message)
    {

        $this->response->setJsonContent(
            [
            'errors' => [
                'message'     => $message,
                'status_code' => 401,
                ],
            ]
        );
        $this->response->setStatusCode(401);
    }
}
