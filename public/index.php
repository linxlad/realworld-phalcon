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
    require_once APP_PATH . '/library/Bootstrap.php';

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