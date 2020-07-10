<?php

namespace Napps\Rest;

use Napps\Rest\Router\Router;
use Napps\Rest\DI\Resolver;
use Napps\Rest\Renderer\PHPRendererInterface;

class App
{
    private $router;
    private $renderer;
    private $pathRepalce = '/srv/';

    public function __construct()
    {
        //$path = $_SERVER['PATH_INFO'] ?? '/';
        //var_dump($_SERVER['PATH_INFO']);
        //var_dump($this->getPATHINFO($_SERVER['REQUEST_URI'])); //PATH_INFO
        $path = $this->getPATHINFO($_SERVER['REQUEST_URI']) ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->router = new Router($path, $method);
    }

    public function setRenderer(PHPRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function get(string $path, $callback)
    {
        $this->router->get($path, $callback);
    }

    public function post(string $path, $callback)
    {
        $this->router->post($path, $callback);
    }

    public function put(string $path, $callback)
    {
        $this->router->put($path, $callback);
    }

    public function delete(string $path, $callback)
    {
        $this->router->delete($path, $callback);
    }

    public function request(string $path, $callback)
    {
        $this->router->request($path, $callback);
    }

    public function run()
    {
        $route = $this->router->run();
        $resolver = new Resolver();

        $data = $resolver->method($route['callback'], [
            'params' => $route['params']
        ]);

        $this->renderer->setData($data);
        $this->renderer->run();
    }

    public function getPATHINFO($path){
        $path_elements = explode("/", $path);
        $tempPI = "";
        if (isset($path_elements[2])){
            for ($i = 2 ;$i < count($path_elements); $i++ )
                $tempPI .= "/".$path_elements[$i];
        }
        return "/" . str_replace($this->pathRepalce, "", $tempPI);
    }
}
