<?php

use Phalcon\Config;

require __DIR__ . '/src/Config/defines.php';

/**
 * @var Config $config
 */
$config = require CONFIG_PATH . 'config.php';

$database = $config->database->postgres;

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/database/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => $database->dbname,
        'default' => [
            'adapter' => $database->adapter,
            'host' => $database->host,
            'name' => $database->dbname,
            'user' => $database->username,
            'pass' => $database->password,
            'port' => $database->port,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
    ],
];