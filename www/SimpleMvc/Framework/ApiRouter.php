<?php

namespace SimpleMvc\Framework;

class ApiRouter
{
    protected array $uriSegments = [];
    protected mixed $controller = null;
    protected array $routes = [];
    protected string $currentRoute = '';

    public function __construct($routes)
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->uriSegments = explode('/', $uri);

        $this->routes = $routes;

        if (isset($this->uriSegments[2])) {
            $this->currentRoute = $this->uriSegments[2];
        }

        $this->initialize();
    }

    protected function initialize()
    {
        if ($this->currentRoute) {
            // launch controller
            if (array_key_exists($this->currentRoute, $this->routes)) {
                $this->controller = new $this->routes[$this->currentRoute]['controller']();
            } else {
                _sendResponse(404, ['error' => 'route not found', 'routes' => $this->routes]);
            }
        } else {
            _sendResponse(200, ['routes' => $this->routes]);
        }
    }
}
