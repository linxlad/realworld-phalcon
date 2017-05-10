<?php

namespace RealWorld\Listener;

use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Dispatcher;

/**
 * Class CorsListener
 * @package RealWorld\Listener
 * @property Request $request
 * @property Response $response
 */
class CorsListener extends Injectable
{
    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher) {
        $di = $dispatcher->getDI();
        $request = $di->get('request');
        $response = $di->get('response');

        if ($this->isCorsRequest($request)) {
            $response
                ->setHeader('Access-Control-Allow-Origin', $this->getOrigin($request))
                ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE')
                ->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization')
                ->setHeader('Access-Control-Allow-Credentials', 'true');
        }

        if ($this->isPreflightRequest($request)) {
            $response->setStatusCode(200, 'OK')->send(); exit;
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isCorsRequest(Request $request)
    {
        return !empty($request->getHeader('Origin')) && !$this->isSameHost($request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isPreflightRequest(Request $request)
    {
        return $this->isCorsRequest($request)
            && $request->getMethod() === 'OPTIONS'
            && !empty($request->getHeader('Access-Control-Request-Method'));
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isSameHost(Request $request)
    {
        return $request->getHeader('Origin') === $this->getSchemeAndHttpHost($request);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getSchemeAndHttpHost(Request $request)
    {
        return $request->getScheme() . '://' . $request->getHttpHost();
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getOrigin(Request $request)
    {
        return $request->getHeader('Origin') ? $request->getHeader('Origin') : '*';
    }
}