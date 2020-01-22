<?php

namespace Backend\Application\Services;


use Phalcon\Config;
use Phalcon\Crypt;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class CryptServiceProvider implements ServiceProviderInterface
{

    public function register(DiInterface $di): void
    {

        /**
         * @var Config $config
         */
        $config = $di->get('config');

        $di->set('crypt', static function () use ($config) {
            $crypt = new Crypt();

            $salt = substr($config->apps->cryptSalt, 0, 32);
            $crypt->setKey($salt);

            return $crypt;
        });
    }
}