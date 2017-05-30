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
