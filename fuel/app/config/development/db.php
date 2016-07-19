<?php

/**
 * The development database settings. These get merged with the global settings.
 */
return array(
    'default' => array(
        'type' => 'pdo',
        'connection' => array(
            'hostname' => 'localhost',
            'port' => '3306',
            'database' => 'homework',
            'username' => 'root',
            'password' => '',
            'persistent' => false,
            'compress' => false,
            'dsn'        => 'mysql:host=127.0.0.1;port=3306;dbname=homework;charset=utf8',
        ),
        'identifier' => '`',
        'table_prefix' => '',
        'charset' => 'utf8',
        'enable_cache' => true,
        'profiling' => false,
        'readonly' => false,
    ),
);
