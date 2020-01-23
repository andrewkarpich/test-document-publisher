<?php

namespace Backend\Server;

use Carbon\Carbon;
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

        $uri = $request->server['request_uri'];

        $this->convertRequest($request);

        $this->consoleLog(Carbon::now()->toDateTimeString() . ' - Handle Request: ' . $uri);

        /**
         * @var Application $application
         */
        $application = new $this->mvcApplicationClassName($this->config);
        $application->getDI()->get('request')->setRawBody($request->rawContent());
        $application->header = $request->header;

        $result = $application->handle($uri);

        if ($result instanceof \Phalcon\Http\Response) {

            $response->status($result->getStatusCode(), $result->getReasonPhrase());

            foreach ($result->getHeaders()->toArray() as $headerName => $header) {
                $response->setHeader($headerName, $header);
            }

            $content = $result->getContent();

            if ($content) {
                $response->end($content);
            }

        } elseif ($result) {

            $response->status($application->response->getStatusCode(), $application->response->getReasonPhrase());

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