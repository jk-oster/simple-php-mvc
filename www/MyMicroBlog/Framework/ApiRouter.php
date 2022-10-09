<?php

namespace MyMicroBlog\Framework;

use Error;

class ApiRouter
{
    protected array $uriSegments = [];
    protected mixed $controller;

    public function __construct($routes)
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->uriSegments = explode('/', $uri);

        try {
            if (isset($this->uriSegments[2])) {
                $controllerName = 'MyMicroBlog\\API\\' . ucfirst($this->uriSegments[2]) . 'ApiController';
                $this->controller = new $controllerName();
            }
            else {
                
            }
        }
        catch (Error $e) {
            _sendResponse(404);
        }
    }
}
