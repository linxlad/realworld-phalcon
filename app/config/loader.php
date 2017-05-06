<?php

$loader = new \Phalcon\Loader();

$loader->registerNamespaces([
    'RealWorld\\Controllers' => \dirname(__DIR__) . '/controllers/',
    'RealWorld\\Models' => \dirname(__DIR__) . '/models/',
]);

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir
    ]
);

$loader->register();
