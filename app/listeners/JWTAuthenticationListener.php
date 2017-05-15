<?php

namespace RealWorld\Listeners;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Phalcon\Config;
use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use Phalcon\Http\Response;
use Phalcon\Mvc\Dispatcher;
use RealWorld\Auth\Auth;

/**
 * Class JWTAuthenticationListener
 * @package RealWorld\Plugin
 * @property Config config
 * @property Auth auth
 */
class JWTAuthenticationListener extends Injectable
{
    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return Response
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        try {
            if ($token = $this->getAuthorization()) {
                $key = $this->config->application->security->salt;
                $decodedToken = JWT::decode($token, $key, ['HS256']);
                $user = $this->auth->loginWithJWT($decodedToken);
                $this->request->user = $user;
            }
        } catch (\Phalcon\Security\Exception $e) {
            return $this->respondError('JWT error: User not found.');
        } catch (\Firebase\JWT\ExpiredException $e) {
            return $this->respondError('JWT error: Token has expired.');
        } catch (\Exception $e) {
            var_dump($e->getMessage()); exit;
        }

        return $this->response;
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

    /**
     * Respond with json error message.
     *
     * @param $message
     * @return Response
     */
    protected function respondError($message)
    {
        $headers = $this->response->getHeaders();
        $headers->set('Content-Type', 'application/json; charset=utf-8');
        $this->response->setHeaders($headers);

        $this->response->setJsonContent([
            'errors' => [
                'message' => $message,
                'status_code' => 401
            ]
        ]);

        $this->response->setStatusCode(401);

        return $this->response->send();
    }
}