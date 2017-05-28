<?php

use League\Fractal\Manager as FractalManager;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use RealWorld\Auth\Auth;
use RealWorld\Middleware\CorsMiddleware;
use RealWorld\Middleware\JWTAuthenticationMiddleware;
use RealWorld\Plugins\DataSerializerPlugin;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ]);

            return $volt;
        },
        '.phtml' => PhpEngine::class

    ]);

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);

    return $connection;
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

// Register the default dispatcher's namespace for controllers
$di->setShared(
    "dispatcher",
    function () use ($di) {
        $eventsManager = new Manager();

        // Attach listeners.
        $eventsManager->attach("dispatch:beforeExecuteRoute", $di->get('cors'));
        $eventsManager->attach("dispatch:beforeExecuteRoute", $di->get('jwt'));

        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($eventsManager);
        $dispatcher->setDefaultNamespace("RealWorld\\Controllers");
        //$dispatcher->setModelBinder(new Binder());

        return $dispatcher;
    }
);

/**
 * Custom authentication component.
 */
$di->set(
    'auth',
    function () {
        return new Auth();
    }
);

$di->setShared('cors', function () {
    return new CorsMiddleware();
});

$di->setShared('jwt', function () {
    return new JWTAuthenticationMiddleware();
});

$di->set('crypt', function () {
    $crypt = new Phalcon\Crypt();
    $key = $this->getConfig()->application->security->salt;
    $crypt->setKey($key);

    return $crypt;
});

$di->setShared('serializer', function () {
   $manager = new FractalManager();
   $manager->setSerializer(new DataSerializerPlugin());

   return $manager;
});

$di->setShared('repository', function () {
    $alias = func_get_args();

    if (isset($alias[0]) && is_array($alias[0])) {
        $alias = reset($alias[0]);
    } elseif (is_array($alias)) {
        $alias = reset($alias);
    }

    $repositoryClassName = sprintf('\RealWorld\Repository\%sRepository', ucfirst(strtolower($alias)));

    if (!class_exists($repositoryClassName)) {
        throw new \Phalcon\Di\Exception('Repository class ' . $repositoryClassName . ' does not exist.');
    }

    return new $repositoryClassName();
});