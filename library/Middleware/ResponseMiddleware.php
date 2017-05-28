<?php

namespace RealWorld\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;

class ResponseMiddleware implements MiddlewareInterface
{
    /**
     * Call me
     *
     * @param Micro $application
     *
     * @return bool
     */
    public function call(Micro $application)
    {
        /** @var Response $response */
        $response = $application->getDI()->getShared('response');
        $response->setContentType('application/json; charset=utf-8');

        $response->send();

        return true;
    }
}
