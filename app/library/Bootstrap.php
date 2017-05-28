<?php

namespace RealWorld;

use Dotenv\Dotenv;
use League\Fractal\Manager as LeagueFractalManager;
use Phalcon\Config as PhConfig;
use Phalcon\Crypt as PhCrypt;
use Phalcon\Di as PhDi;
use Phalcon\Di\FactoryDefault as PhFactoryDefault;
use Phalcon\Exception as PhException;
use Phalcon\Http\Response as PhResponse;
use Phalcon\Http\Response;
use Phalcon\Loader as PhLoader;
use Phalcon\Logger\Adapter\File as PhLoggerFile;
use Phalcon\Logger\Formatter\Line as PhLoggerFormatter;
use Phalcon\Mvc\Micro as PhMicro;
use Phalcon\Mvc\Micro\Collection as PhMicroCollection;
use Phalcon\Mvc\Model\MetaData\Memory as PhMetadataMemory;
use Phalcon\Mvc\Model\MetaData\Files as PhMetadataFiles;
use Phalcon\Security;
use RealWorld\Controllers\IndexController;
use RealWorld\Controllers\UserController;
use RealWorld\Middleware\JWTAuthenticationMiddleware;
use RealWorld\Middleware\ResponseMiddleware;
use RealWorld\Plugins\DataSerializerPlugin as RWSerializerPlugin;

use const APP_PATH;

class Bootstrap
{
    /** @var PhMicro  */
    protected $application;

    /** @var PhDi */
    protected $diContainer;

    /** @var string */
    protected $environment = 'dev';

    public function run()
    {
        /**
         * Initialize the Di Container
         */
        $this->diContainer = new PhFactoryDefault();
        PhDI::setDefault($this->diContainer);

        /**
         * Initialize the application
         */
        $this->application = new PhMicro($this->diContainer);
        $this->diContainer->setShared('application', $this->application);

        $this
            ->initLoader()
            ->initLogger()
            ->initEnvironment()
            ->initConfig()
            ->initErrorHandler()
            ->initDatabase()
            ->initModelsMetadata()
            ->initRoutes()
            ->initCrypt()
            ->initAuth()
            ->initSecurity()
            ->initSerializer()
        ;


        return $this->runApplication();
    }

    /**
     * @return Bootstrap
     * @throws PhException
     */
    protected function initAuth(): Bootstrap
    {
        $this->diContainer->setShared(
            'auth',
            function () {
                return new Auth();
            }
        );

        return $this;
    }

    /**
     * @return Bootstrap
     * @throws PhException
     */
    protected function initConfig(): Bootstrap
    {
        $this->diContainer->setShared(
            'config',
            function () {
                $fileName = APP_PATH . '/app/config/config.php';
                if (true !== file_exists($fileName)) {
                    throw new PhException('Configuration file not found');
                }

                $configArray       = require_once($fileName);
                $config            = new PhConfig($configArray);
                $this->environment = $config->get('application')->get('env', 'dev');

                return $config;
            }
        );

        return $this;
    }

    /**
     * @return Bootstrap
     */
    protected function initCrypt(): Bootstrap
    {
        $this->diContainer->setShared(
            'crypt',
            function () {
                $crypt = new PhCrypt();
                $key   = $this
                    ->diContainer
                    ->get('config')
                    ->get('application')
                    ->get('security')
                    ->get('salt');
                $crypt->setKey($key);

                return $crypt;
            }
        );

        return $this;
    }

    /**
     * @return Bootstrap
     */
    protected function initDatabase(): Bootstrap
    {
        $this->diContainer->setShared(
            'db',
            function () {
                /** @var PhConfig $config */
                $config  = $this->diContainer->get('config');
                $section = $config->get('database');
                $class   = 'Phalcon\Db\Adapter\Pdo\\' . $section->get('adapter');
                $params  = [
                    'host'     => $section->get('host'),
                    'username' => $section->get('username'),
                    'password' => $section->get('password'),
                    'dbname'   => $section->get('dbname'),
                    'charset'  => $section->get('charset'),
                ];

                if ($section->get('adapter') == 'Postgresql') {
                    unset($params['charset']);
                }

                /**
                 * Get the connection
                 */
                return new $class($params);
            }
        );

        return $this;
    }

    /**
     * @return Bootstrap
     */
    protected function initEnvironment(): Bootstrap
    {
        (new Dotenv(APP_PATH))->load();

        return $this;
    }

    /**
     * @return Bootstrap
     */
    protected function initErrorHandler(): Bootstrap
    {
        ini_set('display_errors', ('prod' !== $this->environment));
        error_reporting(E_ALL);

        set_error_handler(
            function ($errorNumber, $errorString, $errorFile, $errorLine) {
                if (0 === $errorNumber & 0 === error_reporting()) {
                    return;
                }

                /** @var PhLoggerFile $logger */
                $logger = $this->diContainer->getShared('logger');
                if (null !== $logger) {
                    $logger->error(
                        sprintf(
                            "[%s] [%s] %s - %s",
                            $errorNumber,
                            $errorLine,
                            $errorString,
                            $errorFile
                        )
                    );
                }
            }
        );

        return $this;
    }

    /**
     * @return Bootstrap
     */
    protected function initLoader(): Bootstrap
    {
        $loader = new PhLoader();
        $loader->registerNamespaces(
            [
                'RealWorld'              => APP_PATH . '/app/library',
                'RealWorld\Controllers'  => APP_PATH . '/app/controllers',
                'RealWorld\Models'       => APP_PATH . '/app/models',
                'RealWorld\Plugins'      => APP_PATH . '/app/library/plugins',
                'RealWorld\Transformers' => APP_PATH . '/app/library/transformers',
//                'Phalcon'                => APP_PATH . '/app/library $config->application->vendorDir . 'phalcon/incubator/Library/Phalcon/',
            ]
        );

        $loader->register();

        require_once APP_PATH . '/vendor/autoload.php';

        return $this;
    }

    /**
     * @return Bootstrap
     */
    public function initLogger(): Bootstrap
    {
        $this->diContainer->setShared(
            'logger',
            function () {
                $format    = '[%date%][%type%] %message%';
                $logFile   = sprintf(
                    '%s/storage/logs/rw-%s.log',
                    APP_PATH,
                    date('Ymd')
                );
                $formatter = new PhLoggerFormatter($format);
                $logger    = new PhLoggerFile($logFile);
                $logger->setFormatter($formatter);
            }
        );

        return $this;
    }

    /**
     * @return Bootstrap
     */
    public function initModelsMetadata(): Bootstrap
    {
        $this->diContainer->setShared(
            'modelsMetadata',
            function () {
                /**
                 * Production will use File, development will use memory
                 */
                if ('prod' === $this->environment) {
                    $options['metaDataDir'] = APP_PATH . '/storage/metadata/';

                    return new PhMetadataFiles($options);
                } else {
                    return new PhMetadataMemory();
                }
            }
        );

        return $this;
    }

    /**
     * @return Bootstrap
     */
    public function initRoutes(): Bootstrap
    {
        /**
         * 404
         */
        $this->application->notFound(
            function () {
                /** @var PhResponse $response */
                $response = $this->diContainer->get('response');

                $response->setContent('404');
                $response->setStatusCode(404);
            }
        );

        /**
         * @TODO Options
         */
        $routes = [
            [
                'class'   => IndexController::class,
                'methods' => [
                    'get' => [
                        '/' => 'indexAction',
                    ],
                ],
            ],
            [
                'class'   => UserController::class,
                'methods' => [
                    'get' => [
                        '/user' => 'indexAction',
                    ],
                    'put' => [
                        '/user' => 'updateAction',
                    ],
                    'patch' => [
                        '/user' => 'updateAction',
                    ],
                ],
            ],
        ];

        $middleware = [
            [
                'event' => 'before',
                'class' => JWTAuthenticationMiddleware::class,
            ],
            [
                'event' => 'after',
                'class' => ResponseMiddleware::class,
            ],
        ];


        foreach ($routes as $route) {
            $collection = new PhMicroCollection();
            $collection->setHandler($route['class'], true);

            foreach ($route['methods'] as $verb => $methods) {
                foreach ($methods as $endpoint => $action) {
                    $collection->$verb($endpoint, $action);
                }
            }
            $this->application->mount($collection);
        }

        $eventsManager = $this->diContainer->getShared('eventsManager');

        foreach ($middleware as $element) {
            $class = $element['class'];
            $event = $element['event'];
            $eventsManager->attach('micro', new $class());
            $this->application->$event(new $class());
        }

        $this->application->setEventsManager($eventsManager);



        return $this;
    }

    /**
     * @return Bootstrap
     */
    protected function initSecurity(): Bootstrap
    {
        $this->diContainer->setShared(
            'security',
            function () {
                return new Security();
            }
        );

        return $this;
    }

    /**
     * @return Bootstrap
     */
    protected function initSerializer(): Bootstrap
    {
        $this->diContainer->setShared(
            'serializer',
            function () {
                $manager = new LeagueFractalManager();
                $manager->setSerializer(new RWSerializerPlugin());

                return $manager;
            }
        );

        return $this;
    }

    protected function runApplication()
    {
        if ('test' === $this->environment) {
            return $this->application;
        } else {
            return $this->application->handle();
        }
    }
}
