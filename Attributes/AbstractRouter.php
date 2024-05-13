<?php
namespace Tiaras\Router\Attributes;

abstract class AbstractRouter {
    
    protected $app;
    protected $response;

    public function get(String $path, Object|Callable|Array $content)
    {
        $this->app->register(['GET', $path, $content]);
        // return $this;
    }

    public function post(String $path, Object|Callable $content)
    {
        $this->app->register(['POST', $path, $content]);
    }

    public function run(): \Tiaras\Router\Attributes\Response
    {
        return new Response($this->app->getRoute());
    }

    protected function singleton($service): void
    {
        if (empty($this->app)) {
            $this->app = $service::getInstance();
        }
    }

}