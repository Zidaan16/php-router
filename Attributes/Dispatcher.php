<?php
namespace Tiaras\Attributes;
use Tiaras\Http\Request;

class Dispatcher {
    private static $instance;
    private $request;
    private $pattern;
    private $args;
    private $method;
    private $uri;
    private $action;

    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new Dispatcher(new Request);
        }

        return static::$instance;
    }

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->request->byDefault();
    }

    public function withPattern(String $pattern): void
    {
        $this->pattern = $pattern;
    }

    public function register(Array $route)
    {
        $this->method = $route[0];
        $this->uri = $route[1];
        $this->action = $route[2];
        if (!empty($this->pattern)) $this->prefixWithPattern();
        $this->isMatch();
    }

    public function isMatch()
    {
        if ($this->cleaner($this->uri) == $this->cleaner($this->request->path())) {
            if ($this->method == $this->request->method()) {
                call_user_func($this->action, ...$this->args);
                exit;
            } else {
                echo 'Method '.$this->request->method().' not supported for this route';
            }
        }
    }

    private function prefixWithPattern(): void
    {
        $uri = explode('/', $this->uri);
        $path = explode('/', $this->request->path());
        $regex = preg_grep($this->pattern, $uri);
        foreach ($regex as $key => $value) {
            if (empty($path[$key])) break;
            $this->args[substr($value, 1, -1)] = $path[$key];
            $uri[$key] = $path[$key];
        }
        $this->uri = implode('/', $uri);
    }

    public function cleaner(String $path): string
    {
        return trim($this->uri, '/');
    }

}