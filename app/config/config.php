<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'db',
        'username'    => 'root',
        'password'    => 'secret',
        'dbname'      => 'realworlddb',
        'charset'     => 'utf8',
    ],
    'application' => [
        'appDir'            => APP_PATH . '/',
        'controllersDir'    => APP_PATH . '/controllers/',
        'modelsDir'         => APP_PATH . '/models/',
        'migrationsDir'     => APP_PATH . '/migrations/',
        'viewsDir'          => APP_PATH . '/views/',
        'pluginsDir'        => APP_PATH . '/plugins/',
        'libraryDir'        => APP_PATH . '/library/',
        'transformersDir'   => APP_PATH . '/transformers/',
        'cacheDir'          => BASE_PATH . '/cache/',
        'vendorDir'          => BASE_PATH . '/vendor/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),

        'security' => [
            'salt' => '21932302859125c16db30f4.76012023',
        ]
    ]
]);
