<?php

namespace RealWorld\Traits;

use Phalcon\Http\Response;
use Phalcon\Mvc\Model\Message;

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
     * @param array|string $messages
     * @param $statusCode
     * @return Response;
     */
    protected function respondError($messages, $statusCode = 401)
    {
        $errors = null;

        if (is_array($messages)) {
            foreach ($messages as $field => $message) {
                if ($message instanceof Message) {
                    $errors[$message->getField()] = [$message->getMessage()];
                } else {
                    $errors[$field] = [$message];
                }
            }
        }

        $this->response->setJsonContent(
            [
                'errors' => $errors ? [ $errors ] : $messages,
                'status_code' => $statusCode
            ],
            $statusCode
        );

        return $this->response->setStatusCode($statusCode);
    }
}
