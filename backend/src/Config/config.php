<?php

use Phalcon\Config;

$env = getenv('ENVIRONMENT');
if (!$env) $env = 'local';

$config = new Config([

    'env' => $env,

    'server' => [
        'host' => 'localhost',
        'port' => '8080',
    ],

    'database' => [
        'postgres' => [
            'adapter'  => 'pgsql',
            'host'     => 'postgres',
            'dbname'   => 'test',
            'username' => 'test',
            'password' => 'sRZeJuJjR2uy8CX4',
            'port'     => 5432,
        ],
    ],

    'secure' => [
        'method' => 'AES-256-CTR',
        'vector' => 'UbmPw4S3dvk2tb6L3mx3Fp4taUR29CfX',
        'salt'   => '9m3fVMc@857Quf%1?gR#WfC%8Ec%5s&cjPa77N2F!rKK^3B9sHasuLYhuYz-aEZjFPBk',
    ],

]);

/**
 * Merge with env config
 */
if ($env !== 'my') {

    $configFilepath = CONFIG_PATH . "env/config.$env.php";

    if (file_exists($configFilepath)) {

        $envConfig = require $configFilepath;

        if ($envConfig instanceof Config) {
            $config->merge($envConfig);
        }
    }
}

/**
 * Merge with my local config
 */
if (file_exists(CONFIG_PATH . 'env/config.my.php')) {
    $config->merge(require CONFIG_PATH . 'env/config.my.php');
}

return $config;