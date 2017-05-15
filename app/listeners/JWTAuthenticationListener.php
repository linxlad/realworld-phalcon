<?php

namespace RealWorld\Listeners;

use Firebase\JWT\BeforeValidException;
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
            if ($this->hasAuthorizationHeader() && $token = $this->parseToken()) {
                $key = $this->config->application->security->salt;
                $decodedToken = JWT::decode($token, $key, ['HS256']);
                $user = $this->auth->loginWithJWT($decodedToken);
                $this->request->user = $user;
            }
        } catch (\Exception $e) {
            switch (get_class($e)) {
                case 'Phalcon\Security\Exception':
                    $message = 'JWT error: User not found.';
                    break;
                case 'Firebase\JWT\ExpiredException':
                    $message = 'JWT error: Token has expired.';
                    break;
                default:
                    $message = 'JWT error: ' . $e->getMessage() . '.';
            }

            return $this->respondError($message);
        }

        return $this->response;
    }

    /**
     * @return bool|string
     */
    public function hasAuthorizationHeader()
    {
        return $this->request->getHeader('authorization') ?
            $this->request->getHeader('authorization') :
            false;
    }

    /**
     * @param string $header
     * @param string $query
     * @return string
     * @throws BeforeValidException
     */
    public function parseToken($header = 'authorization', $query = 'token')
    {
        if (!$token = $this->parseAuthHeader($header, $query)) {
            if (!$token = $this->request->get($query)) {
                throw new BeforeValidException('The token could not be parsed from the request', 400);
            }
        }

        return $token;
    }

    /**
     * Parse token from the authorization header.
     *
     * @param string $header
     * @param string $query
     *
     * @return false|string
     */
    protected function parseAuthHeader($header = 'authorization', $query = 'token')
    {
        $header = $this->request->getHeader($header);

        if (!$this->startsWith(strtolower($header), $query)) {
            return false;
        }

        return trim(str_ireplace($query, '', $header));
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && substr($haystack, 0, strlen($needle)) === (string) $needle) {
                return true;
            }
        }

        return false;
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

        return $this->response->send(); exit;
    }
}