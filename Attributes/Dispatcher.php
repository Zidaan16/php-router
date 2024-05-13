<?php
namespace Tiaras\Router\Attributes;
use Tiaras\Http\Request;

class Dispatcher {
    private static $instance;
    private bool $cache = false;
    private string $cacheDir;
    private $action;
    private $content;
    private $request;
    private $pattern;
    private $args;
    private $method;
    private $uri;

    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new Dispatcher(new Request);
        }

        return static::$instance;
    }

    public function __construct(Request $request)
    {
        $this->content = ContentManager::getInstance();
        $this->request = $request;
        $this->request->byDefault();
    }

    public function cache(String|null $dir)
    {
        $this->cache = true;
        if (is_null($dir)) $this->cacheDir = dirname(__DIR__)."/cache/";
        else $this->cacheDir = trim($dir, '/');
    }

    private function saveToCache()
    {

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

    private function createArgsFromProperty(): void
    {
        $this->args = $this->content->pack();
        foreach ($this->args as $key => $value) {
            if ($value != 'string') $this->args[$key] = new $value;
        }
    }

    public function isMatch()
    {
        if ($this->cleaner($this->uri) == $this->cleaner($this->request->path())) {
            if ($this->method == $this->request->method()) {
                $this->content->set($this->action);
                $this->createArgsFromProperty();
                // call_user_func($this->content->get(), ...$this->args);

                // exit;
            } else {
                echo 'Method '.$this->request->method().' not supported for this route';
            }
        }
    }

    public function getRoute(): array
    {
        return ['ea'];
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
            // echo $path[$key];
        }
        // print_r($this->args);
        $this->uri = implode('/', $uri);
    }

    public function cleaner(String $path): string
    {
        return trim($path, '/');
    }

}