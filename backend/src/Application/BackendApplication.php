<?php

namespace Backend\Application;

use Backend\Application\Requests\Request;
use Backend\Application\Services\CryptServiceProvider;
use Backend\Application\Services\DatabaseServiceProvider;
use Phalcon\Config;
use Phalcon\Di;
use Phalcon\Filter\FilterFactory;
use Phalcon\Http\Response;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Model\Manager;
use Phalcon\Mvc\Model\MetaData\Memory;
use Phalcon\Mvc\Router;

/**
 * Class BackendApplication
 * @package Backend\Application
 * @property Di $container
 */
class BackendApplication extends Micro
{

    public function __construct(Config $config)
    {

        Di::reset();

        parent::__construct(new Di());

        $this->registerServices($config);

        $this->registerRoutes($config);

        $this->setResponseHandler([$this, 'responseHandle']);
    }

    protected function registerServices(Config $config): void
    {

        $this->container->set('config', $config);
        $this->container->set('request', new Request());
        $this->container->set('response', new Response());
        $this->container->set('router', new Router());
        $this->container->set('modelsManager', new Manager());
        $this->container->set('modelsMetadata', new Memory());
        $this->container->set('eventsManager', new \Phalcon\Events\Manager());
        $this->container->set('filter', (new FilterFactory())->newInstance());

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
        $returnedValue = $this->getReturnedValue();

        if ($returnedValue instanceof Response) {

            return $returnedValue;

        }

        if ($returnedValue) {

            $this->response->setContent($returnedValue);

        }

        return $this->response;
    }

}