<?php

/**
 * APP_PATH is what we need pretty much everywhere
 */

use Phalcon\Di;
use Phalcon\Logger\Adapter\File;
use RealWorld\Bootstrap;

if (!defined('APP_PATH')) {
    define('APP_PATH', dirname(dirname(__FILE__)));
}

try {
    require_once APP_PATH . '/app/library/Bootstrap.php';

    /**
     * We don't want a global scope variable for this
     */
    (new Bootstrap())->run();

} catch (\Exception $e) {
    /**
     * Display the error only if we are in dev mode
     */
    $error = $e->getMessage() . PHP_EOL . $e->getTraceAsString();
    if (false !== getenv('RW_ENV') && 'prod' !== getenv('RW_ENV')) {
        $diContainer = Di::getDefault();
        /** @var File $logger */
        $logger = $diContainer->get('logger');
        if (null !== $logger) {
            $logger->error($error);
        }
    }

    echo $error;
}


//use Phalcon\Di\FactoryDefault;
//use Phalcon\Mvc\Application;
//
//error_reporting(E_ALL);
//
//define('BASE_PATH', dirname(__DIR__));
//define('APP_PATH', BASE_PATH . '/app');
//
//try {
//    /**
//     * The FactoryDefault Dependency Injector automatically registers
//     * the services that provide a full stack framework.
//     */
//    $di = new FactoryDefault();
//
//    /**
//     * Handle routes
//     */
//    include APP_PATH . '/config/router.php';
//
//    /**
//     * Read services
//     */
//    include APP_PATH . '/config/services.php';
//
//    /**
//     * Get config service for use in inline setup below
//     */
//    $config = $di->getConfig();
//
//    /**
//     * Include Autoloader
//     */
//    include APP_PATH . '/config/loader.php';
//
//    /**
//     * Include composer autoloader
//     */
//    require APP_PATH . "/../vendor/autoload.php";
//
//    /**
//     * Handle the request
//     */
//    $application = new Application($di);
//    $request = $application->handle();
//    $request->send();
//} catch (\Exception $e) {
//    echo $e->getMessage() . '<br>';
//    echo '<pre>' . $e->getTraceAsString() . '</pre>';
//}
