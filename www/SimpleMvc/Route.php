<?php

namespace SimpleMvc;

/**
 * Class Route
 *
 * This class represents a route in the application.
 * It is used by the Router to match the request URI to a controller and method.
 *
 * @example
 * $route = new Route('GET', '/user/{id}/entries', 'UserController', 'index', 'default');
 */
class Route implements \JsonSerializable
{
    protected string $verb;
    protected string $route;
    protected array $routeParts = [];
    protected string $formAction;
    protected string $controller;
    protected string $method;
    protected array $middlewares = [];

    /**
     * Returns a unique identifier for the route.
     * @return string - unique identifier for the route e.g., "GET:/user/{id}/entries:index"
     */
    public function getIdentifier(): string
    {
        return "$this->verb:$this->route:$this->formAction";
    }

    public function getVerb(): string
    {
        return $this->verb;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getRouteParts(): array
    {
        return $this->routeParts;
    }

    public function getFormAction(): string
    {
        return $this->formAction;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function __construct(string $verb, string $route, string $controller, string $method, string $formAction = 'default', array $middlewares = [])
    {
        $this->verb = $verb;
        $this->route = $route;
        $this->formAction = $formAction;
        $this->controller = $controller;
        $this->method = $method;
        $this->middlewares = $middlewares;

        $this->routeParts = explode('/', $this->route);

    }

    public static function get(string $route, string $controller, string $method, string $formAction = 'default', array $middlewares = []): Route
    {
        return new self('GET', $route, $controller, $method, $formAction, $middlewares);
    }

    public static function post(string $route, string $controller, string $method, string $formAction = 'default', array $middlewares = []): Route
    {
        return new self('POST', $route, $controller, $method, $formAction, $middlewares);
    }

    public static function put(string $route, string $controller, string $method, string $formAction = 'default', array $middlewares = []): Route
    {
        return new self('PUT', $route, $controller, $method, $formAction, $middlewares);
    }

    public static function delete(string $route, string $controller, string $method, string $formAction = 'default', array $middlewares = []): Route
    {
        return new self('DELETE', $route, $controller, $method, $formAction, $middlewares);
    }

    public static function patch(string $route, string $controller, string $method, string $formAction = 'default', array $middlewares = []): Route
    {
        return new self('PATCH', $route, $controller, $method, $formAction, $middlewares);
    }

    public static function options(string $route, string $controller, string $method, string $formAction = 'default', array $middlewares = []): Route
    {
        return new self('OPTIONS', $route, $controller, $method, $formAction, $middlewares);
    }

    /**
     * Adds a middleware to the route at the end of the middleware stack.
     * @param string $middleware - the middleware to add
     * @return void
     */
    public function addMiddleware(string $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * Checks if the route matches the given verb, route and form action.
     * @param string $verb - the verb to check
     * @param string $route - the route to check
     * @param string $formAction - the form action to check
     * @return bool - true if the route matches, false otherwise
     */
    public function matches(string $verb, string $route, string $formAction = 'default'): bool
    {
        return $this->verb === $verb && $this->matchRoute($route) && $this->formAction === $formAction;
    }

    /**
     * Checks if the route matches the current route.
     * e.g., "/users/1/edit" to "/users/{id}/edit"
     * @param string $route - the route to check
     * @return bool
     */
    private function matchRoute(string $route): bool
    {
        // Check if the route is the same
        if ($this->route === $route) {
            return true;
        }

        // Check if the route has the same number of parts
        $routeParts = explode('/', $route);
        if (count($routeParts) !== count($this->routeParts)) {
            return false;
        }

        // Check if the route parts match
        foreach ($routeParts as $key => $routePart) {
            if ($routePart !== $this->routeParts[$key]) {
                if ($this->routeParts[$key][0] !== '{' || $this->routeParts[$key][strlen($this->routeParts[$key]) - 1] !== '}') {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Parses the route parameters.
     * e.g., "/users/1/edit" to ["id" => 1]
     * @param string $route - the route to parse
     * @return array - the route parameters
     */
    public function parseRouteParams(string $route): array
    {
        $routeParts = explode('/', $route);
        $routeParams = [];
        foreach ($routeParts as $key => $routePart) {
            if ($routePart !== $this->routeParts[$key]) {
                $routeParams[$this->routeParts[$key]] = $routePart;
            }
        }
        return $routeParams;
    }

    /**
     * Runs the registered middlewares for this route.
     * @param Request $request - the request
     * @param Response $response - the response
     * @return void
     */
    private function runMiddlewares(Request $request, Response $response): void
    {
        foreach ($this->middlewares as $middleware) {
            // Call the middleware
            $middleware::handle($request, $response);
        }
    }

    /**
     * Dispatches the route -> runs the middlewares and calls the controller method with the route parameters.
     * @param Request $request - the request
     * @param Response $response - the response
     * @param string $route - the route to dispatch
     * @return void
     */
    public function dispatch(Request $request, Response $response, string $route = ''): void
    {
        $this->runMiddlewares($request, $response);
        $controller = new $this->controller($request, $response);
        $controller->{$this->method}($this->parseRouteParams($route !== '' ? $route : $request->route()));
    }

    public function jsonSerialize(): array
    {
        return [
            'verb' => $this->verb,
            'route' => $this->route,
            'formAction' => $this->formAction,
            'controller' => $this->controller,
            'method' => $this->method,
            'middlewares' => $this->middlewares
        ];
    }
}