<?php
namespace Tiaras\Attributes;

class ContentManager {
    private static $instance;
    private $content;
    
    public static function getInstance(\Closure $content)
    {
        if (empty(static::$instance)) {
            static::$instance = new ContentManager($content);
        }

        return static::$instance;
    }

    public function __construct(\Closure $content)
    {
        $this->content = $content;
    }

    public function getName()
    {
        print_r($this->content);
    }
}