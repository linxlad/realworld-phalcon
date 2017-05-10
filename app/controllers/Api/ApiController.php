<?php

namespace RealWorld\Controllers\Api;

use Phalcon\Http\Response;
use Phalcon\Http\Response\Headers;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Message;

/**
 * Following the standards set by gothinkster.
 * @link https://github.com/gothinkster/laravel-realworld-example-app/blob/master/app/Http/Controllers/Api/ApiController.php
 *
 * Class ApiController
 * @package RealWorld\Controllers\Api
 */
class ApiController extends Controller
{
    /**
     * Returns a json representation of the data.
     *
     * @param $data
     * @param int $statusCode
     * @param array $headersArray
     * @return Response
     */
    protected function respond($data, $statusCode = 200, $headersArray = [])
    {
        $this->response->setJsonContent($data);
        $this->response->setStatusCode($statusCode);

        $headers = $this->response->getHeaders();
        $headers->set('Content-Type', 'application/json; charset=utf-8');

        foreach ($headersArray as $name => $value) {
            $headers->set($name, $value);
        }

        $this->response->setHeaders($headers);

        return $this->response;
    }

    /**
     * Send a success response.
     *
     * @return Response
     */
    protected function respondSuccess()
    {
        return $this->respond(null);
    }

    /**
     * Send a created response.
     *
     * @param $data
     * @return Response;
     */
    protected function respondCreated($data)
    {
        return $this->respond($data, 201);
    }

    /**
     * Send a no content response.
     *
     * @param $data
     * @return Response;
     */
    protected function respondNoContent($data)
    {
        return $this->respond($data, 503);
    }

    /**
     * Send a no content response.
     *
     * @param array|string $messages
     * @param $statusCode
     * @return Response;
     */
    protected function respondError($messages, $statusCode = 422)
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

        return $this->respond(
            [
                'errors' => [
                    $errors ?? $messages
                ]
            ],
            $statusCode
        );
    }

    /**
     * Send a failed login response.
     *
     * @return Response
     */
    protected function respondFailedLogin()
    {
        return $this->respond([
            'errors' => [
                'email or password' => [
                    'is invalid'
                ],
            ]
        ], 422);
    }

    /**
     * Send a unauthorized response.
     *
     * @param string $message
     * @return Response
     */
    protected function respondUnauthorized($message = 'Unauthorized')
    {
        return $this->respondError($message, 401);
    }

    /**
     * Send a forbidden response.
     *
     * @param string $message
     * @return Response
     */
    protected function respondForbidden($message = 'Forbidden')
    {
        return $this->respondError($message, 403);
    }

    /**
     * Send a not found response.
     *
     * @param string $message
     * @return Response
     */
    protected function respondNotFound($message = 'Not Found')
    {
        return $this->respondError($message, 404);
    }
}