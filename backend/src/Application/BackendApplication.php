<?php

namespace Backend\Application;

use Backend\Application\Services\CryptServiceProvider;
use Backend\Application\Services\DatabaseServiceProvider;
use Phalcon\Config;
use Phalcon\Di\FactoryDefault;
use Phalcon\Http\Request;
use Phalcon\Mvc\Micro;

class BackendApplication extends Micro
{

    /**
     * @var FactoryDefault
     */
    protected $container;

    public function __construct(Config $config)
    {

        parent::__construct(new FactoryDefault());

        $this->registerServices($config);

        $this->registerRoutes($config);

        $this->setResponseHandler([$this, 'responseHandle']);
    }

    protected function registerServices(Config $config): void
    {

        $this->container->set('config', $config);
        $this->container->set('request', Request::class);

        $this->container->register(new DatabaseServiceProvider());
        $this->container->register(new CryptServiceProvider());

    }

    protected function registerRoutes(Config $config): void
    {

        $routesCollections = require CONFIG_PATH . 'routes.php';

        if (is_array($routesCollections)) {

            foreach ($routesCollections as $routesCollection) {

                if ($routesCollection instanceof Micro\Collection) {

                    $this->mount($routesCollection);

                }
            }

        } elseif ($routesCollections instanceof Micro\Collection) {

            $this->mount($routesCollections);

        }

        $this->notFound([$this, 'notFound404']);

    }

    protected function notFound404(): void
    {
        $this->response->setStatusCode(404, 'Not Found');
    }

    protected function responseHandle()
    {

        $this->response->setContent($this->getReturnedValue());

        return $this->response;

    }

}