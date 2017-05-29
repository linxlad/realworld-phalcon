<?php

namespace RealWorld\Middleware;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\JWT;
use Phalcon\Config;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phalcon\Mvc\User\Plugin;
use RealWorld\Auth;
use RealWorld\Traits\ResponseErrorTrait;
use function var_dump;

/**
 * Class JWTAuthenticationMiddleware
 *
 * @package RealWorld\Middleware
 *
 * @property Auth   $auth
 * @property Config $config
 */
class JWTAuthenticationMiddleware extends Plugin implements MiddlewareInterface
{
    use ResponseErrorTrait;

    public function beforeExecuteRoute()
    {
        try {
            if ($this->hasAuthorizationHeader() && $token = $this->parseToken()) {
                $key          = $this->config->security->salt;
                $decodedToken = JWT::decode($token, $key, ['HS256']);

                if (!$this->auth->loginWithJWT($decodedToken)) {
                    throw new \Exception('That token does not belong to a user.');
                }
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
                    $message = 'JWT error: ' . $e->getMessage();
            }

            $this->respondError($message);
            $this->response->send();

            return false;
        }

        return true;
    }

    /**
     * @param Micro $application
     * @return bool
     */
    public function call(Micro $application)
    {
        return true;
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
     *
     * @return string
     *
     * @throws BeforeValidException
     */
    public function parseToken($header = 'authorization', $query = 'token')
    {
        if (!$token = $this->parseAuthHeader($header, $query)) {
            if (!$token = $this->request->get($query)) {
                throw new BeforeValidException(
                    'The token could not be parsed from the request',
                    400
                );
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
     * @param  string       $haystack
     * @param  string|array $needles
     *
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
}