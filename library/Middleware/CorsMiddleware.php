<?php

namespace RealWorld\Middleware;

use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;

/**
 * Class CorsMiddleware
 * @package RealWorld\Listeners
 */
class CorsMiddleware extends Injectable implements MiddlewareInterface
{
    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        if ($this->isCorsRequest()) {
            $this->response
                ->setHeader('Access-Control-Allow-Origin', $this->getOrigin())
                ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE')
                ->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization')
                ->setHeader('Access-Control-Allow-Credentials', 'true');
        }

        if ($this->isPreflightRequest()) {
            $this->response->setStatusCode(200, 'OK')->send(); exit;
        }
    }

    public function call(Micro $application)
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isCorsRequest()
    {
        return !empty($this->request->getHeader('Origin')) && !$this->isSameHost();
    }

    /**
     * @return bool
     */
    public function isPreflightRequest()
    {
        return $this->isCorsRequest()
            && $this->request->getMethod() === 'OPTIONS'
            && !empty($this->request->getHeader('Access-Control-Request-Method'));
    }

    /**
     * @return bool
     */
    public function isSameHost()
    {
        return $this->request->getHeader('Origin') === $this->getSchemeAndHttpHost();
    }

    /**
     * @return string
     */
    public function getSchemeAndHttpHost()
    {
        return $this->request->getScheme() . '://' . $this->request->getHttpHost();
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->request->getHeader('Origin') ? $this->request->getHeader('Origin') : '*';
    }
}