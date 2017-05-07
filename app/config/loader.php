<?php

$loader = new \Phalcon\Loader();

$loader->registerNamespaces([
    'RealWorld\\Controllers' => $config->application->controllersDir,
    'RealWorld\\Models' => $config->application->modelsDir,
    'RealWorld' => $config->application->libraryDir,
]);

/**
 * We're a registering a set of directories taken from the configuration file
 */
//$loader->registerDirs(
//    [
//        $config->application->controllersDir,
//        $config->application->modelsDir
//    ]
//);

$loader->register();
