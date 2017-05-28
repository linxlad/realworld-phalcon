<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */

/**
 * Returning an array that will be injected in the relevant Config object
 * in the Bootstrap file
 */
return [
    'application' => [
        'env' => getenv('RW_ENV'),
    ],
    'database'    => [
        'adapter'  => 'Mysql',
        'host'     => getenv('RW_MYSQL_HOST'),
        'username' => getenv('RW_MYSQL_USER'),
        'password' => getenv('RW_MYSQL_PASS'),
        'dbname'   => getenv('RW_MYSQL_NAME'),
        'charset'  => 'utf8',
    ],
    'security'    => [
        'salt'     => '21932302859125c16db30f4.76012023',
    ]
];

//defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
//defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');
//
//return new \Phalcon\Config([
//    'database' => [
//        'adapter'     => 'Mysql',
//        'host'        => 'db',
//        'username'    => 'root',
//        'password'    => 'secret',
//        'dbname'      => 'realworlddb',
//    ],
//    'application' => [
//        'appDir'            => APP_PATH . '/',
//        'controllersDir'    => APP_PATH . '/controllers/',
//        'modelsDir'         => APP_PATH . '/models/',
//        'migrationsDir'     => APP_PATH . '/migrations/',
//        'viewsDir'          => APP_PATH . '/views/',
//        'pluginsDir'        => APP_PATH . '/plugins/',
//        'libraryDir'        => APP_PATH . '/library/',
//        'transformersDir'   => APP_PATH . '/transformers/',
//        'cacheDir'          => BASE_PATH . '/cache/',
//        'vendorDir'          => BASE_PATH . '/vendor/',
//
//        // This allows the baseUri to be understand project paths that are not in the root directory
//        // of the webpspace.  This will break if the public/index.php entry point is moved or
//        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
//        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
//
//        ]
//    ]
//]);
