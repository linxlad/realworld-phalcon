<?php

use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;

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
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
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
    function () {
        $eventsManager = $this->getShared('eventsManager');
        $eventsManager->attach('dispatch:afterExecuteRoute', function($event, $dispatcher, $exception) {
                $this->get('view')->disableLevel(array(
                    View::LEVEL_ACTION_VIEW => true,
                    View::LEVEL_LAYOUT => true,
                    View::LEVEL_MAIN_LAYOUT => true,
                    View::LEVEL_AFTER_TEMPLATE => true,
                    View::LEVEL_BEFORE_TEMPLATE => true
                ));

                $this->get('response')->setContentType('application/json', 'UTF-8');

                // Hook to afterRender event.
                if (null == $this->get('view')->getEventsManager()) {
                    $eventManager = new Manager();
                    $this->get('view')->setEventsManager($eventManager);
                } else {
                    $eventManager = $this->get('view')->getEventsManager();
                }

                $eventManager->attach("view:afterRender", function ($event, $view) use($dispatcher) {
                    $data = $dispatcher->getReturnedValue();

                    if (is_array($data)) {
                        $data['success'] = $data['success'] ?? true;
                        $data['message'] = $data['message'] ?? '';
                        $data = json_encode($data);
                    }

                    $view->setContent($data);
                });
        });

        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace("RealWorld\\Controllers");
        $dispatcher->setEventsManager($eventsManager);

        return $dispatcher;
    }
);
