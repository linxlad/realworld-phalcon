<?php

namespace RealWorld\Listener;

use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use Phalcon\Mvc\Dispatcher;

/**
 * Class PreFlightListener
 * @package RealWorld\Listener
 */
class PreFlightListener extends Injectable
{
    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return mixed
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher) {
        $di = $dispatcher->getDI();
        $request = $di->get('request');
        $response = $di->get('response');
        $origin = $request->getHeader('ORIGIN') ? $request->getHeader('ORIGIN') : '*';

        $response
            ->setHeader('Access-Control-Allow-Origin', $origin)
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE')
            ->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization')
            ->setHeader('Access-Control-Allow-Credentials', 'true');

        if (strtoupper($request->getMethod()) == 'OPTIONS') {
            $response->setStatusCode(200, 'OK')->send(); exit;
        }
    }
}