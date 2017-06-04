<?php

namespace RealWorld\Controllers\Api;

use function is_array;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
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
     * @var bool
     */
    protected $modelPaginated = false;

    /**
     * @param $bool
     * @return $this
     */
    public function setModelPaginated($bool)
    {
        $this->modelPaginated = $bool;

        return $this;
    }

    /**
     * Returns a json representation of the data.
     *
     * @param $data
     * @param int   $statusCode
     * @param array $headersArray
     * @return Response
     */
    protected function respond($data, int $statusCode = 200, array $headersArray = [])
    {
        $this->response->setJsonContent($data);
        $this->response->setStatusCode($statusCode);

        $headers = $this->response->getHeaders();
        $headers->set('Content-Type', 'application/json; charset=utf-8');

        foreach ($headersArray as $name => $value) {
            $headers->set($name, $value);
        }

        return $this->response->setHeaders($headers);
    }

    /**
     * @param $data
     * @param Transformer $transformer
     * @param int         $statusCode
     * @param array       $headerArray
     *
     * @return Response|array
     */
    public function respondWithTransformer(
        $data,
        Transformer $transformer,
        int $statusCode = 200,
        array $headerArray = []
    ) {
        $this->validateTransformer($transformer);
        $key = $transformer->getResourceKey();

        if ($data instanceof Resultset || is_array($data)) {
            $data = new Collection($data, $transformer, $key . 's');
        } else {
            $data = new Item($data, $transformer, $key);
        }

        $serializer = $this->getDI()->get('serializer');
        $out = $serializer->createData($data)->toArray();

        return $this->modelPaginated ? $out : $this->respond($out, $statusCode, $headerArray);
    }

    /**
     * @param $data
     * @param $limit
     * @param $page
     * @return mixed
     */
    public function paginate($data, $limit, $page)
    {
        $paginator = new PaginatorModel(
            array(
                "data" => $data,
                "limit" => $limit,
                "page" => $page
            )
        );

        $this->modelPaginated = true;

        return $paginator->getPaginate()->items;
    }

    /**
     * Send a success response.
     *
     * @return Response
     */
    protected function respondSuccess(): Response
    {
        return $this->respond(null);
    }

    /**
     * Send a created response.
     *
     * @param $data
     * 
     * @return Response
     */
    protected function respondCreated($data): Response
    {
        return $this->respond($data, 201);
    }

    /**
     * Send a no content response.
     *
     * @param $data
     *
     * @return Response
     */
    protected function respondNoContent($data): Response
    {
        return $this->respond($data, 503);
    }

    /**
     * Send a failed login response.
     *
     * @return Response
     */
    protected function respondFailedLogin(): Response
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
    protected function respondUnauthorized($message = 'Unauthorized'): Response
    {
        return $this->respondError($message, 401);
    }

    /**
     * Send a forbidden response.
     *
     * @param string $message
     * @return Response
     */
    protected function respondForbidden($message = 'Forbidden'): Response
    {
        return $this->respondError($message, 403);
    }

    /**
     * Send a not found response.
     *
     * @param string $message
     * @return Response
     */
    protected function respondNotFound($message = 'Not Found'): Response
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
