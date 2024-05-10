<?php
namespace Tiaras\Attributes;

abstract class AbstractRouter {
    
    protected $app;

    public function get(String $path, Object|Callable $content)
    {
        $this->app->register(['GET', $path, $content]);
    }

    public function post(String $path, Object|Callable $content)
    {
        $this->app->register(['POST', $path, $content]);
    }

    protected function singleton($service): void
    {
        if (empty($this->app)) {
            $this->app = $service::getInstance();
        }
    }

}