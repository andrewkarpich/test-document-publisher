<?php

namespace Backend\Application\Services;


use Phalcon\Config;
use Phalcon\Db\Adapter\Pdo\Postgresql;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class DatabaseServiceProvider implements ServiceProviderInterface
{

    public function register(DiInterface $di): void
    {

        /**
         * @var Config $config
         */
        $config = $di->get('config');

        $di->set('db', function () use ($di, $config) {

            $eventsManager = $di->get('eventsManager');

            $descriptor = [
                'host'     => $config->database->postgres->host,
                'username' => $config->database->postgres->username,
                'password' => $config->database->postgres->password,
                'dbname'   => $config->database->postgres->dbname,
                'port'     => $config->database->postgres->port
            ];

            if (isset($config->database->postgres->dsn)) {
                $descriptor['dsn'] = $config->database->postgres->dsn;
            }

            $connection = new Postgresql($descriptor);

            $connection->setEventsManager($eventsManager);

            return $connection;

        });

    }
}