<?php

namespace RealWorld;

use const APP_PATH;
use Dotenv\Dotenv;
use Phalcon\Config;
use Phalcon\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Exception;
use Phalcon\Http\Response;
use Phalcon\Loader;
use Phalcon\Mvc\Micro;

class Bootstrap
{
    /** @var Micro  */
    protected $application;

    /** @var Di */
    protected $diContainer;

    /** @var string */
    protected $environment = 'dev';

    public function run()
    {
        /**
         * Initialize the Di Container
         */
        $this->diContainer = new FactoryDefault();
        DI::setDefault($this->diContainer);

        /**
         * Initialize the application
         */
        $this->application = new Micro($this->diContainer);
        $this->diContainer->setShared('application', $this->application);

        $this
            ->initLoader()
            ->initEnvironment()
            ->initConfig()
            ->initRoutes();


        return $this->runApplication();
    }

    /**
     * @return Bootstrap
     * @throws Exception
     */
    protected function initConfig(): Bootstrap
    {
        $fileName = APP_PATH . '/app/config/config.php';
        if (true !== file_exists($fileName)) {
            throw new Exception('Configuration file not found');
        }

        $configArray = require_once($fileName);
        $config = new Config($configArray);
        $this->environment = $config->get('application')->get('env', 'dev');

        $this->diContainer->setShared('config', $config);

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
    protected function initLoader(): Bootstrap
    {
        $loader = new Loader();
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
    public function initRoutes(): Bootstrap
    {
        $this->application->notFound(
            function () {
                /** @var Response $response */
                $response = $this->diContainer->get('response');

                $response->setContent('404');
                $response->setStatusCode(404);
                $response->send();
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
