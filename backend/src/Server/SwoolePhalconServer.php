<?php

namespace Backend\Server;

use Phalcon\Config;
use Phalcon\Mvc\Application;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

class SwoolePhalconServer extends Server
{

    /**
     * @var Application
     */
    protected $mvcApplicationClassName;

    /**
     * @var Config
     */
    protected $config;

    public function run(string $mvcApplicationClassName, Config $config)
    {

        $this->mvcApplicationClassName = $mvcApplicationClassName;
        $this->config = $config;

        $this->on('start', [$this, 'onStart']);
        $this->on('request', [$this, 'onRequest']);

        return $this->start();
    }

    public function onStart(Server $server): void
    {

        $this->consoleLog('Swoole http server is started at http://' . $this->host . ':' . $this->port . '/');

    }

    public function onRequest(Request $request, Response $response): void
    {

        $this->convertRequest($request);

        $uri = $request->server['request_uri'];

        $this->consoleLog('Handle Request: ' . $uri);

        /**
         * @var Application $application
         */
        $application = new $this->mvcApplicationClassName($this->config);

        $result = $application->handle($uri);

        $response->status($application->response->getStatusCode(), $application->response->getReasonPhrase());

        if ($result instanceof \Phalcon\Http\Response) {
            $response->end($result->getContent());
        } elseif ($result) {
            $response->end($result);
        } else {
            $response->end();
        }
    }

    /**
     * Convert Swoole HTTP request to regular PHP request
     * @param Request $request
     */
    protected function convertRequest(Request $request): void
    {

        foreach ($request->server as $key => $value) {
            $_SERVER[strtoupper($key)] = $value;
        }

        $_GET = $request->get;
        $_POST = $request->post;
        $_COOKIE = $request->cookie;
        $_FILES = $request->files;

        $_REQUEST = array_merge(
            (array)$request->get,
            (array)$request->post,
            (array)$request->cookie
        );
    }

    public function consoleLog($string): void
    {
        echo $string . PHP_EOL;
    }

}