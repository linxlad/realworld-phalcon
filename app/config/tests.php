<?php

/**
 * APP_PATH is what we need pretty much everywhere
 */

use Phalcon\Di;
use Phalcon\Logger\Adapter\File;
use RealWorld\Bootstrap;
use RealWorld\BootstrapTests;

defined('APP_PATH') || define('APP_PATH', getenv('APP_PATH') ?: dirname(dirname(dirname(__FILE__))));

try {
    putenv('RW_ENV=test');

    require_once APP_PATH . '/library/Bootstrap.php';
    require_once APP_PATH . '/library/BootstrapTests.php';

    /**
     * We don't want a global scope variable for this
     */
    return (new BootstrapTests())->run();

} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL . $e->getTraceAsString();
}
