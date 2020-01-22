<?php

use Backend\Application\BackendApplication;
use Backend\Server\SwoolePhalconServer;

require __DIR__ . '/Config/defines.php';

$config = require CONFIG_PATH . 'config.php';

require VENDOR_PATH . 'autoload.php';

$server = new SwoolePhalconServer($config->server->host, $config->server->port);

$server->run(BackendApplication::class, $config);