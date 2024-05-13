<?php
namespace Tiaras\Router\Attributes;

class ContentManager {
    private static $instance;
    private $content;
    private Object|null $class;
    private $reflection;
    private $params;
    
    public static function getInstance(): object|callable
    {
        if (empty(static::$instance)) {
            static::$instance = new ContentManager();
        }
        
        return static::$instance;
    }

    public function set(\Closure|array $content): void
    {
        $this->class = null;
        if (!is_array($content)) $this->reflection = new \ReflectionFunction($content);
        else $this->reflection = $this->withClass($content);
        $this->params = $this->check();
    }

    public function get(): \Closure
    {
        if (!empty($this->class)) return $this->reflection->getClosure($this->class);
        else return $this->reflection->getClosure();
    }

    public function check(Bool $options = true): array
    {
        if ($options) {
            $param = $this->reflection->getParameters();
            foreach ($param as $key => $value) {
                if (!class_exists($param[$key]->getType()) && $param[$key]->getType() != 'string') die("Class ".$param[$key]->getType()." not exists");
            }
        }

        return $param;
    }

    private function withClass(Array $content)
    {
        $this->class = new $content[0]();
        return new \ReflectionMethod($this->class, $content[1]);
    }

    public function getParametersType()
    {
        $result = [];
        foreach ($this->params as $key => $value) {
            $result[] = $this->params[$key]->getType()->getName();
        }
        return $result;
    }

    public function getParametersName()
    {
        $result = [];
        foreach ($this->params as $key => $value) {
            $result[] = $this->params[$key]->getName();
        }
        return $result;
    }

    public function pack()
    {
        return array_combine($this->getParametersName(), $this->getParametersType());
    }

}