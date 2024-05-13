<?php
namespace Tiaras\Router\Attributes;

class Response {

    public function __construct(Callable|Object $content, Int $httpResponse = 200)
    {
        http_response_code($httpResponse);
        call_user_func($content);
        exit;
    }

}