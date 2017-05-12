<?php

namespace RealWorld\Listeners;

use Firebase\JWT\JWT;
use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use Phalcon\Mvc\Dispatcher;

/**
 * Class JWTAuthenticationListener
 * @package RealWorld\Plugin
 */
class JWTAuthenticationListener extends Injectable
{
    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        try {
            if ($token = $this->getAuthorization()) {
                $key = $this->getDI()->get('config')->application->security->salt;
                $decoded = JWT::decode($token, $key, ['HS256']);
                var_dump($decoded); exit;
            }
        } catch (\Exception $e) {
            var_dump($e); exit;
        }
    }

    /**
     * @return bool|string
     */
    public function getAuthorization()
    {
        return $this->request->getHeader('Authorization') ?
            $this->request->getHeader('Authorization') :
            false;
    }
}