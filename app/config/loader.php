<?php

$loader = new \Phalcon\Loader();

$loader->registerNamespaces([
    'RealWorld\\Controllers' => $config->application->controllersDir,
    'RealWorld\\Models' => $config->application->modelsDir,
    'RealWorld\\Plugins' => $config->application->pluginsDir,
    'RealWorld' => $config->application->libraryDir,
]);

$loader->register();
