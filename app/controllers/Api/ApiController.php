<?php

namespace RealWorld\Controllers\Api;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Resultset;
use RealWorld\Traits\ResponseErrorTrait;
use RealWorld\Transformers\Transformer;

/**
 * Following the standards set by gothinkster/laravel-realworld-example-app.
 * @link https://github.com/gothinkster/laravel-realworld-example-app/blob/master/app/Http/Controllers/Api/ApiController.php
 *
 * Class ApiController
 * @package RealWorld\Controllers\Api
 * @property Manager serializer
 */
class ApiController extends Controller
{
    use ResponseErrorTrait;

    /**
     * Returns a json representation of the data.
     *
     * @param $data
     * @param int $statusCode
     * @param array $headersArray
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
    }

    /**
     * @param $data
     * @param $transformer
     * @param int $statusCode
     * @param array $headerArray
     * @return Response
     */
    public function respondWithTransformer($data, $transformer, $statusCode = 200, $headerArray = [])
    {
        $this->validateTransformer($transformer);
        $key = $transformer->getResourceKey();

        if ($data instanceof Resultset) {
            $data = new Collection($data, $transformer,  $key . 's');
        } else {
            $data = new Item($data, $transformer, $key);
        }

        $serializer = $this->getDI()->get('serializer');
        $out = $serializer->createData($data)->toArray();

        return $this->respond($out, $statusCode, $headerArray);
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

    /**
     * @param $transformer
     * @throws \Exception
     */
    private function validateTransformer($transformer)
    {
        if (!$transformer instanceof Transformer) {
            throw new \Exception('Not instance of Transformer.');
        }
    }

    /**
     * @param $alias
     * @return mixed
     * @throws \Exception
     */
    public function getJsonInput($alias)
    {
        if (!$userInput = $this->request->getJsonRawBody(true)[$alias]) {
            throw new \Exception('No input with alias ' . $alias . '.');
        }

        return $userInput;
    }
}