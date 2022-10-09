<?php

namespace MyMicroBlog\Framework;

class ApiRouter
{
    protected array $uriSegments = [];
    protected mixed $controller;

    public function __construct($routes)
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->uriSegments = explode('/', $uri);

        if (isset($this->uriSegments[2])) {
            $currentRoute = $this->uriSegments[2];

            if (array_key_exists($currentRoute, $routes)) {
                $this->controller = new $routes[$currentRoute]['controller']();
            } else {
                _sendResponse(404, ['error' => 'route not found', 'routes' => $routes]);
            }
        } else {
            _sendResponse(200, ['routes' => $routes]);
        }
    }
}
