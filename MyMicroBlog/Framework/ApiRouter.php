<?php

namespace MyMicroBlog\Framework;

class ApiRouter {
    protected array $uriSegments = [];
    protected mixed $controller;

    public function __construct()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->uriSegments = explode('/', $uri);

        $controllerName = 'MyMicroBlog\\API\\' . ucfirst($this->uriSegments[3]) . 'ApiController';

        if(file_exists($controllerName . '.php')){
            $this->controller = new $controllerName();
        }
        else {
            ob_clean();
            header_remove();
            header('HTTP/1.1 404 Not Found');
            exit();
        }
    }
}
