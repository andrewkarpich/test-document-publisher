<?php

require __DIR__ . '/../src/Config/defines.php';

$config = require CONFIG_PATH . 'config.php';

require VENDOR_PATH . 'autoload.php';

$app = new Backend\Application\BackendApplication($config);