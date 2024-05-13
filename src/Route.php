<?php
namespace Tiaras\Services;
use Tiaras\Router\Attributes\AbstractRouter;
use Tiaras\Router\Attributes\Dispatcher;

class Route extends AbstractRouter {

    public function __construct()
    {
        $this->singleton(Dispatcher::class);
    }

    public function pattern(String $pattern)
    {
        $this->app->withPattern("/$pattern/");
    }

}