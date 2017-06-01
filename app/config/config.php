<?php
/**
 * Returning an array that will be injected in the relevant Config object
 * in the Bootstrap file
 */
return [
    'database'    => [
        'adapter'  => 'Mysql',
        'host'     => getenv('RW_MYSQL_HOST'),
        'username' => getenv('RW_MYSQL_USER'),
        'password' => getenv('RW_MYSQL_PASS'),
        'dbname'   => getenv('RW_MYSQL_NAME'),
        'charset'  => 'utf8',
    ],
    'application' => [
        'env' => getenv('RW_ENV'),
        'appDir'         => APP_PATH . '/app',
        'commandsDir'    => APP_PATH . '/app/console/commands',
        'consoleDir'     => APP_PATH . '/app/console/',
        'databaseDir'    => APP_PATH . '/app/database/',
        'migrationsDir'  => APP_PATH . '/app/database/migrations/',
        'modelsDir'      => APP_PATH . '/app/models/',
    ],
    'security'    => [
        'salt'     => '21932302859125c16db30f4.76012023',
    ],
];
